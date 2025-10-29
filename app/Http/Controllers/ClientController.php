<?php

namespace App\Http\Controllers;

use App\Exceptions\ClientNotFoundException;
use App\Filters\ClientGlobalSearchFilter;
use App\Filters\DateBetweenFilter;
use App\Http\Requests\RegisterClientRequest;
use App\Http\Resources\ClientProfileResource;
use App\Http\Resources\ClientResource;
use App\Http\Resources\ReferenceContactResource;
use App\Http\Resources\RegisterClientResource;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ClientController extends Controller
{

    public function index()
    {

        $clients = QueryBuilder::for(Client::class)
            ->where('company_id', Auth::user()->company_id)
            ->allowedFilters(
                AllowedFilter::exact('person_type'),
                AllowedFilter::exact('status'),
                AllowedFilter::custom('search', new ClientGlobalSearchFilter()),
                AllowedFilter::custom('created_at', new DateBetweenFilter()),
            )
            ->paginate(10)
            ->appends(request()->query());


        return [

            'clients' => ClientResource::collection($clients),
            
            'meta' => [
                'current_page' => $clients->currentPage(),
                'last_page' => $clients->lastPage(),
                'links' => $clients->toArray()['links'] ?? [],
            ],
        ];
    }

    public function statistics()
    {
        $companyId = Auth()->user()->company_id;

        //TOTAL

        $totalClients = Client::where('company_id', $companyId)->get();

        $totalClientsThisMonth = Client::where('company_id', $companyId)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

            $totalNewClientsInThisMonth = $totalClients->count() - $totalClientsThisMonth;

            //ACTIVE

        $totalActiveClientsLastMonth = Client::where('company_id', $companyId)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereStatus('Ativo')
            ->count();

        $totalActiveClientsThisMonth = Client::where('company_id', $companyId)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereStatus('Ativo')
            ->count();

        $percentClientsActive = 0;

        if ($totalActiveClientsLastMonth > 0) {
            $percentClientsActive = (($totalActiveClientsThisMonth - $totalActiveClientsLastMonth) / $totalActiveClientsLastMonth) * 100;
        }

        //DUE

        $totalDueClientsLastMonth = Client::where('company_id', $companyId)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereStatus('Vencido')
            ->count();

        $totalDueClientsThisMonth = Client::where('company_id', $companyId)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereStatus('Vencido')
            ->count();  

        $percentClientsDue = 0;
        if ($totalDueClientsLastMonth > 0) {
            $percentClientsDue = (($totalDueClientsThisMonth - $totalDueClientsLastMonth) / $totalDueClientsLastMonth) * 100;
        }

        //QUITADO

        $totalPaidOffClientsLastMonth = Client::where('company_id', $companyId)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereStatus('Quitado')
            ->count();

        $totalPaidOffClientsThisMonth = Client::where('company_id', $companyId)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereStatus('Quitado')
            ->count();

        $percentClientsPaidOff = 0;
        if ($totalPaidOffClientsLastMonth > 0) {
            $percentClientsPaidOff = (($totalPaidOffClientsThisMonth - $totalPaidOffClientsLastMonth) / $totalPaidOffClientsLastMonth) * 100;
        }


        return [
            'clients_count' => $totalClients->count(),
            'clients_active' => $totalClients->where('status', 'Ativo')->count(),
            'clients_due' => $totalClients->where('status', 'Vencido')->count(),
            'clients_paid_off' => $totalClients->where('status', 'Quitado')->count(),
            'clients_new_this_month' => $totalNewClientsInThisMonth,
            'percent_client_active_this_month_vs_last_month' => round($percentClientsActive, 2),
            'percent_client_due_this_month_vs_last_month' => round($percentClientsDue, 2),
            'percent_client_paid_off_this_month_vs_last_month' => round($percentClientsPaidOff, 2),

            'totalActiveClientsLastMonth' => $totalActiveClientsLastMonth,
            'totalActiveClientsThisMonth' => $totalActiveClientsThisMonth,
            'totalDueClientsLastMonth' => $totalDueClientsLastMonth,
            'totalDueClientsThisMonth' => $totalDueClientsThisMonth,
            'totalPaidOffClientsLastMonth' => $totalPaidOffClientsLastMonth,
            'totalPaidOffClientsThisMonth' => $totalPaidOffClientsThisMonth





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

        $client = DB::transaction(function () use ($input) {


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

            return $client;
        });


        return new RegisterClientResource($client);
    }

    public function update($id, Request $request)
    {

        $client = DB::transaction(function () use ($id, $request) {

            $client = Client::find($id);

            if (!$client) {

                throw new ClientNotFoundException();
            }

            $client->update($request->all());

            $client->profile->update($request->all());

            return $client;
        });

        return $client;
    }

    public function destroy($id)
    {
        $client = Client::find($id);

        if (!$client) {

            throw new ClientNotFoundException();
        }

        $client->delete();
    }
}
