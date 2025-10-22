<?php

namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateFieldsInClientAfterPayment
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {

        $payment = $event->payment;
        $payment_type = $payment->payment_type;
        $liberation = $event->liberation;
        $isFator = $event->isFator;
        $solicitation = $liberation->solicitation;
        $client = $liberation->client;

        if (!$isFator) {

            $paymentsForLiberation = $liberation->payments()->count();

            $payments = $liberation->payments()->latest()->take(2)->get();


            if ($paymentsForLiberation > 1) {

                $penultPayment = $payments[1]->total;

                $latestPayment = $payments[0]->total;

                $client->debit = $client->debit + $latestPayment - $penultPayment;
            } else {

                $client->debit = $client->debit - $solicitation->total + $payment->total;
            }
        }

        switch ($payment_type) {
            case 'Total':
                $client->status = 'Quitado';
                $liberation->status = 'Quitado';
                break;
            default:
                $client->status = 'Ativo';
                $liberation->status = 'Ativo';
                break;
        }

        $paymentDate = now();

        $createdAt = $liberation->created_at;

        $fixedDay = $liberation->due_day ?? $createdAt->day;

        $expiration = Carbon::create($paymentDate->year, $paymentDate->month, $fixedDay);

        if ($expiration->lt($paymentDate)) {
            $expiration->addMonth();
        }

        $liberation->expiration_date = $expiration;

        $liberation->save();
        $client->save();
    }
}
