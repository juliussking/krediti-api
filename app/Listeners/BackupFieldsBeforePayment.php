<?php

namespace App\Listeners;

use App\Events\BackupClient;
use App\Events\BackupLiberation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class BackupFieldsBeforePayment
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

        DB::transaction(function () use ($event) {

            
            $liberation = $event->liberation;
            $payment = $liberation->payments()->latest()->first();
            $client = $event->liberation->client;

            $liberation->backups()->create([
                'amount' => $liberation->amount,
                'status' => $liberation->status,
                'expiration_date' => $liberation->expiration_date,

            ]);

            $client->backups()->create([
                'debit' => $client->debit,
                'status' => $client->status

            ]);

            if($payment){
                
                $payment->backups()->create([
                    'amount' => $payment->amount,
                    'total' => $payment->total,
                    'fator' => $payment->fator,
                    'payment_type' => $payment->payment_type
                ]);

            }


        });
    }
}
