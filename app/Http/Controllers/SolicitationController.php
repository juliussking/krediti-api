<?php

namespace App\Http\Controllers;

use App\Events\ApproveSolicitation;
use App\Exceptions\SolicitationNotFoundException;
use App\Http\Requests\SolicitationRequest;
use App\Http\Resources\SolicitationResource;
use App\Models\Liberation;
use App\Models\Solicitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitationController extends Controller
{
    public function index()
    {
        $solicitations = Solicitation::where('company_id', auth()->user()->company_id)->paginate(10);

        return [
            'solicitations' => SolicitationResource::collection($solicitations),
            'meta' => [
                'solicitations_count' => $solicitations->count(),
                'solicitations_approved' => $solicitations->where('status', 'Aprovada')->count(),
                'solicitations_pending' => $solicitations->where('status', 'Pendente')->count(),
                'solicitations_reproved' => $solicitations->where('status', 'Recusada')->count(),
                'current_page' => $solicitations->currentPage(),
                'last_page' => $solicitations->lastPage(),
            ]
        ];
    }

    public function store($id, SolicitationRequest $request)
    {
        $input = $request->validated();

        $solicitation = Solicitation::create([
            'user_id' => Auth()->user()->id,
            'client_id' => $id,
            'tax' => $input['tax'],
            'amount_requested' => $input['amount_requested'],
            'total' => $request->amount_requested * $request->tax,
            'company_id' => Auth()->user()->company_id
        ]);

        $solicitation->save();

        return response()->json([
            'Solicitation' => 'SolicitationStoreSuccess',
        ]);
    }

    public function update($id, Request $request) {} //CRIAR LÓGICA DE UPDATE

    public function approve($id)
    {
        $solicitation = DB::transaction(function () use ($id) {

            $solicitation = Solicitation::findOrFail($id);

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

            return [
                $solicitation,
                'msg' => 'SolicitationApproveSuccess',
            ];
        });

        ApproveSolicitation::dispatch($solicitation);
    }

    public function recuse($id)
    {
        $solicitation = Solicitation::findOrFail($id);

        $solicitation->status = 'Recusada';
        $solicitation->save();

        return response()->json([
            'msg' => 'SolicitationRecuseSuccess',
        ]);
    }

    public function cancel($id)
    {
        $solicitation = Solicitation::findOrFail($id);

        $solicitation->status = 'Cancelada';
        $solicitation->save();

        return response()->json([
            'msg' => 'SolicitationCancelSuccess',
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
