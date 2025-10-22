<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class RestoreFieldsAfterSolicitationRecused
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
            $solicitation_backup = $event->solicitation->backups()->latest()->first();
            $solicitation = $event->solicitation;

            $liberation = $solicitation->liberation()->latest()->first();

            $solicitation->update([
                'amount_requested' => $solicitation_backup->amount_requested,
                'counteroffer' => $solicitation_backup->counteroffer,
                'amount_approved' => $solicitation_backup->amount_approved,
                'tax' => $solicitation_backup->tax,
                'total' => $solicitation_backup->total,
                'status' => "Recusada"
            ]);

            $client_backup = $event->solicitation->client->backups()->latest()->first();
            $client = $event->solicitation->client;

            $client->update([
                'debit' => $client->debit -= $solicitation_backup->total,
                'status' => $client_backup->status
            ]);

            $solicitation_backup->delete();
            $client_backup->delete();
            $liberation->delete();
        });
    }
}
