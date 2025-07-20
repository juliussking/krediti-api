<?php

namespace App\Http\Controllers;

use App\Http\Requests\SolicitationRequest;
use App\Http\Resources\SolicitationResource;
use App\Models\Liberation;
use App\Models\Solicitation;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class SolicitationController extends Controller
{
    public function index()
    {
        $solicitations = Solicitation::where('company_id', auth()->user()->company_id)->get();

        return [
            'solicitations' => SolicitationResource::collection($solicitations),
            'solicitations_count' => $solicitations->count(),
            'solicitations_approved' => $solicitations->where('status', 'Aprovada')->count(),
            'solicitations_pending' => $solicitations->where('status', 'Pendente')->count(),
            'solicitations_reproved' => $solicitations->where('status', 'Recusada')->count(),
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
            'Solicitation' => 'Solicitação criada com sucesso!',
        ]);
    }

    public function approve($id)
    {
        $solicitation = Solicitation::findOrFail($id);

        if($solicitation->counteroffer) {

            $solicitation->amount_approved = $solicitation->counteroffer;
            $solicitation->total = $solicitation->counteroffer * $solicitation->tax;

        }else{

            $solicitation->amount_approved = $solicitation->amount_requested;
        }

        $solicitation->user_id = Auth()->user()->id;
        $solicitation->status = 'Aprovada';

        $solicitation->save();

        $solicitation->client->status = 'Ativo';
        $solicitation->client->save();

        $liberation = Liberation::create([
            'user_id' => Auth()->user()->id,
            'client_id' => $solicitation->client_id,
            'amount' => $solicitation->amount_approved,
            'status' => 'Ativo',
            'expiration_date' => now()->addDays(30),
            'company_id' => Auth()->user()->company_id
        ]);

        return response()->json([
            'Solicitation' => 'Solicitação aprovada com sucesso!',
            'total' => $solicitation->total,
            'approved_value' => $solicitation->counteroffer ?? $solicitation->amount_requested,

        ]);

    }

    public function recuse($id)
    {
        $solicitation = Solicitation::findOrFail($id);

        $solicitation->status = 'Recusada';
        $solicitation->save();

        return response()->json([
            'Solicitation' => 'Solicitação recusada com sucesso!',
        ]);
    }

    public function counteroffer($id, Request $request)
    {
        $input = $request->validate([
            'counteroffer' => ['required', 'numeric'],
            'tax' => ['required', 'string'],
        ]);

        $solicitation = Solicitation::findOrFail($id);

        $solicitation->counteroffer = $input['counteroffer'];
        $solicitation->tax = $input['tax'];
        $solicitation->total = $input['counteroffer'] * $input['tax'];
        $solicitation->status = 'Contra-oferta';

        $solicitation->save();

        return response()->json([
            'Solicitation' => 'Solicitação enviada com sucesso!',
            'counteroffer' => $solicitation->counteroffer,
            'total' => $solicitation->counteroffer * $solicitation->tax,
            'tax' => $solicitation->tax
        ]);
    }

    public function destroy($id)
    {
        $solicitation = Solicitation::findOrFail($id);

        if(!$solicitation) {
            return response()->json([
                'error' => 'Solicitação não encontrada',
            ]);
        }

        $solicitation->delete();


    }
}
