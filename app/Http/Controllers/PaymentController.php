<?php

namespace App\Http\Controllers;

use App\Events\PaymentCreated;
use App\Exceptions\ExceedsTotalException;
use App\Exceptions\LiberationNotFoundException; // Corrigido
use App\Exceptions\ThisLiberationIsPaidOffException;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Liberation;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function show()
    {
        $payments = Payment::where('company_id', auth()->user()->company_id)->paginate(10);

        return [
            'payments' => PaymentResource::collection($payments),
            'meta' => [
                'payments_count' => $payments->count(),
                'payments_total' => $payments->where('payment_type', 'Total')->count(),
                'payments_fator' => $payments->where('payment_type', 'Fator')->count(),
                'payments_parcial' => $payments->where('payment_type', 'Parcial')->count(),
                'current_page' => $payments->currentPage(),
                'last_page' => $payments->lastPage(),
            ]
        ];
    }

    public function store($id, PaymentRequest $request)
    {
        $request->validated();

        $payment = DB::transaction(function () use ($id, $request) {
            
            $liberation = Liberation::find($id);

            if (!$liberation) {
                throw new LiberationNotFoundException();
            }

            if ($liberation->status === 'Quitado') {

                throw new ThisLiberationIsPaidOffException();
            }

            $solicitation = $liberation->solicitation;
            $amount = $request->amount;
            $hasPayments = $liberation->payments()->exists();

            if (!$hasPayments) {
                $total = $solicitation->total;
                $baseBalance = $total - $amount;
                $fator = $total - $solicitation->amount_approved;
            } else {
                $lastPayment = $liberation->payments()->latest()->first();
                $total = $lastPayment->total;
                $baseBalance = $total - $amount;
                $fator = $lastPayment->fator;
            }
            if ($amount > $total || $amount < $fator) {
                throw new ExceedsTotalException();
            }

            $paymentType = match (true) {
                $amount == $total => 'Total',
                $amount == $fator => 'Fator',
                default => 'Parcial',
            };

            $tax = $solicitation->tax;

            $newPayment = Payment::create([
                'user_id' => auth()->id(),
                'client_id' => $liberation->client_id,
                'liberation_id' => $liberation->id,
                'amount' => $amount,
                'base_balance' => $baseBalance,
                'total' => $baseBalance * $tax,
                'payment_type' => $paymentType,
                'company_id' => auth()->user()->company_id,
                'fator' => ($baseBalance * $tax) - $baseBalance,
            ]);


            if ($amount !== $fator) {

                $paymentsForLiberation = $liberation->payments()->count();

                $payments = $liberation->payments()->latest()->take(2)->get();

                $client = $liberation->client;

                if ($paymentsForLiberation > 1) {

                    $penultPayment = $payments[1]->total;

                    $latestPayment = $payments[0]->total;

                    $client->debit = $client->debit + $latestPayment - $penultPayment;

                } else {

                    $client->debit = $client->debit - $solicitation->total + $newPayment->total;
                }
            }

            switch ($newPayment->payment_type) {
                case 'Total':
                    $client->status = 'Quitado';
                    $liberation->status = 'Quitado';
                    break;
                default:
                    $client->status = 'Ativo';
                    $liberation->status = 'Ativo';
                    break;
            }

            $liberation->save();
            $client->save();

            return $newPayment;
        });

        return response()->json([
            'msg' => 'PaymentStoreSuccess',
            'payment' => new PaymentResource($payment),
        ]);
    }
}
