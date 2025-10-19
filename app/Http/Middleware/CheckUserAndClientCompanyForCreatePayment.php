<?php

namespace App\Http\Middleware;

use App\Exceptions\ThisLiberationIsPaidOffException;
use App\Exceptions\UserAndClientCompanyDontMatchException;
use App\Models\Liberation;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAndClientCompanyForCreatePayment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        $id = $request->route('id');

        $liberation = Liberation::find($id);

        $client = $liberation->client;

        if ($user->company_id !== $client->company_id) {

            throw new UserAndClientCompanyDontMatchException();
        }

        if ($liberation->status == 'Quitado') {

            throw new ThisLiberationIsPaidOffException();
        }


        return $next($request);
    }
}
