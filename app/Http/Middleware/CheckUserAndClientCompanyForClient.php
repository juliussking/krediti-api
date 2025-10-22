<?php

namespace App\Http\Middleware;

use App\Exceptions\ClientNotFoundException;
use App\Exceptions\UserAndClientCompanyDontMatchException;
use App\Models\Client;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAndClientCompanyForClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route('id');

        $user = auth()->user();

        $client = Client::find($id);

        if (!$client) {

            throw new ClientNotFoundException();
        }

        $clientCompany = $client->company->id;

        $userCompany = $user->company->id;

        $companyMatch = $clientCompany === $userCompany;

        if (!$companyMatch) {

            throw new UserAndClientCompanyDontMatchException();
        }

        return $next($request);
    }
}
