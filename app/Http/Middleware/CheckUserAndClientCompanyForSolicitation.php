<?php

namespace App\Http\Middleware;

use App\Exceptions\SolicitationNotFoundException;
use App\Exceptions\UserAndClientCompanyDontMatchException;
use App\Models\Solicitation;
use Closure;
use Illuminate\Http\Request;

class CheckUserAndClientCompanyForSolicitation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $id = $request->route('id');

        $solicitation = Solicitation::find($id);

        if (!$solicitation) {

            throw new SolicitationNotFoundException();
        }

        $user_company = $request->user()->company->id;

        $client_company = $solicitation->client->company->id;

        if ($user_company !== $client_company) {

            throw new UserAndClientCompanyDontMatchException();
        }

        return $next($request);
    }
}
