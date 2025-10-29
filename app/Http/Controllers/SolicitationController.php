<?php

namespace App\Http\Controllers;

use App\Events\ApproveSolicitation;
use App\Events\BeforeApproveSolicitation;
use App\Events\SolicitationRecused;
use App\Exceptions\SolicitationHasBeenApprovedException;
use App\Exceptions\SolicitationHasBeenRecusedException;
use App\Exceptions\SolicitationNotFoundException;
use App\Filters\ClientGlobalSearchFilter;
use App\Filters\DateBetweenFilter;
use App\Filters\UserIdNameFilter;
use App\Http\Requests\SolicitationRequest;
use App\Http\Resources\SolicitationResource;
use App\Models\Solicitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SolicitationController extends Controller
{
    public function index()
    {
        $solicitations = QueryBuilder::for(Solicitation::class)
            ->where('company_id', Auth::user()->company_id) // filtro fixo
            ->allowedFilters([
                AllowedFilter::custom('user_id', new UserIdNameFilter()),
                AllowedFilter::custom('client_id', new UserIdNameFilter()),
                AllowedFilter::exact('status'),
                AllowedFilter::partial('amount_requested'),
                AllowedFilter::partial('counteroffer'),
                AllowedFilter::partial('amount_approved'),
                AllowedFilter::partial('tax'),
                AllowedFilter::partial('total'),
                AllowedFilter::custom('created_at', new DateBetweenFilter()),
                AllowedFilter::custom('search', new ClientGlobalSearchFilter()),
            ])
            ->paginate(10);

            $totalSolicitations = Solicitation::where('company_id', Auth()->user()->company_id)->get();

        return [
            'solicitations' => SolicitationResource::collection($solicitations),
            'meta' => [
                'solicitations_total' => $totalSolicitations->count(),
                'solicitations_approved' => $totalSolicitations->where('status', 'Aprovada')->count(),
                'solicitations_pending' => $totalSolicitations->where('status', 'Pendente')->count(),
                'solicitations_reproved' => $totalSolicitations->where('status', 'Recusada')->count(),

                'solicitations_filter_count' => $solicitations->count(),
                'solicitations_filter_approved' => $solicitations->where('status', 'Aprovada')->count(),
                'solicitations_filter_pending' => $solicitations->where('status', 'Pendente')->count(),
                'solicitations_filter_reproved' => $solicitations->where('status', 'Recusada')->count(),

                'links' => $solicitations->toArray()['links'] ?? [],


                'current_page' => $solicitations->currentPage(),
                'last_page' => $solicitations->lastPage(),
            ]
        ];
    }

    public function statistics()
    {
        $solicitation = Solicitation::where('company_id', Auth()->user()->company_id)->get();

        return [

            'solicitations_count' => $solicitation->count(),
            'solicitations_approved' => $solicitation->where('status', 'Aprovada')->count(),
            'solicitations_pending' => $solicitation->where('status', 'Pendente')->count(),
            'solicitations_reproved' => $solicitation->where('status', 'Recusada')->count(),
        ];
    }

    public function store($id, SolicitationRequest $request)
    {
        $input = $request->validated();

        Solicitation::create([
            'user_id' => Auth()->user()->id,
            'client_id' => $id,
            'tax' => $input['tax'],
            'amount_requested' => $input['amount_requested'],
            'total' => $request->amount_requested * $request->tax,
            'company_id' => Auth()->user()->company_id
        ]);

        return response()->json([
            'Solicitation' => 'SolicitationStoreSuccess',
        ]);
    }

    public function update($id, SolicitationRequest $request)
    {
        $input = $request->validated();

        $solicitation = Solicitation::find($id);

        if (!$solicitation) {

            throw new SolicitationNotFoundException();
        }

        $amount = $input['amount_requested'] ?? $solicitation->amount_requested;
        $tax = $input['tax'] ?? $solicitation->tax;

        $solicitation->update([
            'amount_requested' => $amount,
            'tax' => $tax,
            'total' => $amount * $tax,
        ]);
    }

    public function approve($id)
    {


        $solicitation = DB::transaction(function () use ($id) {

            $solicitation = Solicitation::find($id);


            if (!$solicitation) {

                throw new SolicitationNotFoundException();
            }


            if ($solicitation->status === 'Aprovado' || $solicitation->amount_approved > 0) {

                throw new SolicitationHasBeenApprovedException();
            }

            BeforeApproveSolicitation::dispatch($solicitation);

            if ($solicitation->counteroffer) {

                $solicitation->amount_approved = $solicitation->counteroffer;
                $solicitation->total = $solicitation->counteroffer * $solicitation->tax;
            } else {

                $solicitation->amount_approved = $solicitation->amount_requested;
            }

            $solicitation->user_id = Auth()->user()->id;
            $solicitation->status = 'Aprovada';

            $solicitation->save();

            $client = $solicitation->client;

            $client->status = 'Ativo';
            $client->debit += $solicitation->total;
            $client->save();

            return
                $solicitation;
        });

        ApproveSolicitation::dispatch($solicitation);

        return response()->json([
            'msg' => 'SolicitationApproveSuccess',
        ]);
    }

    public function recuse($id)
    {
        $solicitation = Solicitation::find($id);

        if (!$solicitation) {

            throw new SolicitationNotFoundException();
        } elseif ($solicitation->status === 'Recusada') {

            throw new SolicitationHasBeenRecusedException();
        }

        if ($solicitation->status === 'Aprovada') {

            SolicitationRecused::dispatch($solicitation);

            return;
        }

        $solicitation->status = 'Recusada';

        $solicitation->save();

        return response()->json([
            'msg' => 'SolicitationRecuseSuccess',
        ]);
    }

    public function counteroffer($id, Request $request)
    {

        $solicitation = Solicitation::find($id);

        if (!$solicitation) {

            throw new SolicitationNotFoundException();
        }

        $input = $request->validate([
            'counteroffer' => ['required', 'numeric'],
            'tax' => ['required', 'string'],
        ]);

        $solicitation->counteroffer = $input['counteroffer'];
        $solicitation->tax = $input['tax'];
        $solicitation->total = $input['counteroffer'] * $input['tax'];
        $solicitation->status = 'Contra-oferta';

        $solicitation->save();

        return response()->json([
            'msg' => 'SolicitationCounterofferSuccess',
            'counteroffer' => $solicitation->counteroffer,
            'total' => $solicitation->total,
            'tax' => $solicitation->tax
        ]);
    }

    public function destroy($id)
    {
        $solicitation = Solicitation::find($id);

        if (!$solicitation) {

            throw new SolicitationNotFoundException();
        }

        $solicitation->delete();

        return response()->json([
            'msg' => 'SolicitationDestroySuccess',
        ]);
    }
}
