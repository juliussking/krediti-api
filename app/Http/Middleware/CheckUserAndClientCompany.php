<?php

namespace App\Http\Middleware;

use App\Exceptions\ClientNotFoundException;
use App\Exceptions\LiberationNotFoundException;
use App\Exceptions\PaymentNotFoundException;
use App\Exceptions\SolicitationNotFoundException;
use App\Exceptions\TaskNotFoundException;
use App\Exceptions\UserAndClientCompanyDontMatchException;
use App\Models\Client;
use App\Models\Liberation;
use App\Models\Payment;
use App\Models\Solicitation;
use App\Models\Task;
use Closure;
use Illuminate\Http\Request;

class CheckUserAndClientCompany
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $model)
    {
        $id = $request->route('id');

        switch ($model) {
            case 'solicitation':
                $query = Solicitation::find($id) ?? throw new SolicitationNotFoundException();
                break;
            case 'payment':
                $query = Payment::find($id) ?? throw new PaymentNotFoundException();
                break;
            case 'liberation':
                $query = Liberation::find($id) ?? throw new LiberationNotFoundException();
                break;
            case 'client':
                $query = Client::find($id) ?? throw new ClientNotFoundException();
                break;
            case 'task':
                $query = Task::find($id) ?? throw new TaskNotFoundException();
                break;
        }


        $user_company = $request->user()->company->id;
        $client_company = $query->client->company->id;


        if ($user_company == $client_company) {

            throw new UserAndClientCompanyDontMatchException();
        }

        return $next($request);
    }
}
