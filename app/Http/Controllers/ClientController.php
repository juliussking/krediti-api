<?php

namespace App\Http\Controllers;

use App\Exceptions\ClientNotFoundException;
use App\Http\Requests\RegisterClientRequest;
use App\Http\Resources\ClientProfileResource;
use App\Http\Resources\ClientResource;
use App\Http\Resources\ReferenceContactResource;
use App\Http\Resources\RegisterClientResource;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::with('profile')
            ->where('company_id', Auth()->user()->company_id)
            ->get();

        return [

            'clients' => ClientResource::collection($clients),
        ];
    }

    public function statistics()
    {
        $client = Client::where('company_id', Auth()->user()->company_id)->get();
        return [

            'clients_count' => $client->count(),
            'clients_active' => $client->where('status', 'Ativo')->count(),
            'clients_due' => $client->where('status', 'Vencido')->count(),
            'clients_paid_off' => $client->where('status', 'Quitado')->count(),
        ];
    }
    public function show($id)
    {
        $clients = Client::with('profile', 'address', 'document', 'referenceContacts', 'office', 'liberations', 'payments', 'solicitations')
            ->withCount(['liberations', 'payments', 'solicitations'])
            ->withSum('payments', 'amount')
            ->find($id);

        if (!$clients) {

            throw new ClientNotFoundException();
        }

        $referenceContacts = $clients->referenceContacts()->paginate(10);

        return [
            'client' => new ClientProfileResource($clients),
            'referenceContacts' => ReferenceContactResource::collection($referenceContacts),
        ];
    }

    public function store(RegisterClientRequest $request)
    {
        $input = $request->validated();

        $client = Client::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'person_type' => $input['person_type'],
            'status' => 'Novo',
            'company_id' => Auth()->user()->company_id
        ]);

        $client->profile()->create([
            'birth_date' => $input['birth_date'],
            'gender' => $input['gender'],
            'phone' => $input['phone'],
            'marital_status' => $input['marital_status']
        ]);

        $client->address()->create([
            'zipcode' => $input['zipcode'],
            'street' => $input['street'],
            'city' => $input['city'],
            'neighbor' => $input['neighbor'],
            'number' => $input['client_number'],
            'reference_point' => $input['reference_point'],
        ]);

        $client->office()->create([
            'name' => $input['office_name'],
            'phone' => $input['office_phone'],
            'zipcode' => $input['office_zipcode'],
            'street' => $input['office_street'],
            'city' => $input['office_city'],
            'neighbor' => $input['office_neighbor'],
            'number' => $input['office_number'],
            'cnpj' => $input['office_cnpj'],
            'role' => $input['office_role'],
            'salary' => $input['office_salary'],
            'payment_date' => $input['office_payment_date'],
            'admission_date' => $input['office_admission_date'],

        ]);

        $client->solicitations()->create([
            'user_id' => auth()->user()->id,
            'amount_requested' => $input['amount_requested'],
            'tax' => $input['tax'],
            'total' => $input['amount_requested'] * $input['tax'],
            'company_id' => Auth()->user()->company_id
        ]);

        foreach ($input['reference_contacts'] as $referenceContact) {
            $client->referenceContacts()->create([
                'name' => $referenceContact['name'],
                'phone' => $referenceContact['phone'],
                'relation' => $referenceContact['relation'],
            ]);
        }

        return new RegisterClientResource($client);
    }

    public function update($id, Request $request)
    {
        $client = Client::findOrFail($id);

        $client->update($request->all());

        $client->profile->update($request->all());

        return $client;
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);

        $client->delete();
    }
}
