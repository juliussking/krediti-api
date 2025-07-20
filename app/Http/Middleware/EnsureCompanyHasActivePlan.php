<?php

namespace App\Http\Middleware;

use App\Exceptions\CompanyDontHasSubscriptionException;
use App\Exceptions\CompanyNotFoundException;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyHasActivePlan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        $company = $user->company;

        if (!$company) {
            
            throw new CompanyNotFoundException();
        }

        // Verifica se há uma assinatura ativa no Stripe
        if (!$company->subscribed('Krediti')) 
            {

            throw new CompanyDontHasSubscriptionException();
        }

        return $next($request);
    
    }
}
