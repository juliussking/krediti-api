<?php

namespace App\Http\Middleware;

use App\Exceptions\PaymentNotFoundException;
use App\Exceptions\UserAndClientCompanyDontMatchException;
use App\Models\Payment;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAndClientCompanyForPayment
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

        $payment = Payment::find($id);

        $client = $payment->client;

        if (!$payment) {

            throw new PaymentNotFoundException();
        }


        if ($user->company_id !== $client->company_id) {

            throw new UserAndClientCompanyDontMatchException();
        }

        return $next($request);
    }
}
