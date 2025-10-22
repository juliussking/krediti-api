<?php

namespace App\Http\Controllers;

use App\Events\BeforePayment;
use App\Events\PaymentCreated;
use App\Exceptions\ExceedsTotalException;
use App\Exceptions\LiberationNotFoundException; // Corrigido
use App\Exceptions\PaymentNotFoundException;
use App\Exceptions\ThisLiberationIsPaidOffException;
use App\Exceptions\ValueBelowMinimumException;
use App\Filters\ClientIdNameFilter;
use App\Filters\DateBetweenFilter;
use App\Filters\UserIdNameFilter;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Liberation;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PaymentController extends Controller
{
    public function show()
    {
        $payments = QueryBuilder::for(Payment::class)
            ->where('company_id', Auth::user()->company_id) // filtro fixo da empresa
            ->allowedFilters([

                AllowedFilter::exact('id'),
                AllowedFilter::partial('amount'),
                AllowedFilter::partial('client_debit'),
                AllowedFilter::partial('payment_type'),
                AllowedFilter::custom('user_id', new UserIdNameFilter()), //ESSE FILTRO VERIFICA POR NOME * APLICAR NO FRONT *
                AllowedFilter::custom('client_id', new ClientIdNameFilter()), //ESSE FILTRO VERIFICA POR NOME * APLICAR NO FRONT *
                AllowedFilter::custom('created_at', new DateBetweenFilter()),
            ])
            ->paginate(10);


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

        [$payment, $isFator, $liberation] = DB::transaction(function () use ($id, $request) {

            $liberation = Liberation::find($id);

            BeforePayment::dispatch($liberation);

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
            if ($amount > $total) {

                throw new ExceedsTotalException();
            } elseif ($amount < $fator) {

                throw new ValueBelowMinimumException();
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

            $isFator = ($fator == $amount);

            return [$newPayment, $isFator, $liberation];
        });

        PaymentCreated::dispatch(
            $payment,
            $isFator,
            $liberation
        );

        return response()->json([
            'msg' => 'PaymentStoreSuccess',
            'payment' => new PaymentResource($payment),
        ]);
    }

    public function update($id, PaymentRequest $request)
    {
        $payment = Payment::find($id);

        if (!$payment) {

            throw new PaymentNotFoundException();
        }

        $liberation_id = $payment->liberation->id;

        $clientBackup = $payment->client->backups()->latest()->first();

        $payment->client->update([

            'debit' => $clientBackup->debit,
            'status' => $clientBackup->status,

        ]);

        $liberationBackup = $payment->liberation->backups()->latest()->first();

        $payment->liberation->update([

            'amount' => $liberationBackup->amount,
            'status' => $liberationBackup->status,

        ]);

        $payment->delete();

        $clientBackup->delete();

        $liberationBackup->delete();

        $this->store($liberation_id, $request);
    }

    public function destroy($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {

            throw new PaymentNotFoundException();
        }

        $clientBackup = $payment->client->backups()->latest()->first();

        $payment->client->update([

            'debit' => $clientBackup->debit,
            'status' => $clientBackup->status,

        ]);

        $liberationBackup = $payment->liberation->backups()->latest()->first();

        $payment->liberation->update([

            'amount' => $liberationBackup->amount,
            'status' => $liberationBackup->status,

        ]);

        $clientBackup->delete();

        $liberationBackup->delete();

        $payment->delete();
    }
}
