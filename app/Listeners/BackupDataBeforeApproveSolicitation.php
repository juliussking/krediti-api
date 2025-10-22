<?php

namespace App\Listeners;

use App\Events\BackupClient;
use App\Events\BackupSolicitation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class BackupDataBeforeApproveSolicitation
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
            $solicitation = $event->solicitation;
            $client = $solicitation->client;

            $solicitation->backups()->create([
                'amount_requested' => $solicitation->amount_requested,
                'counteroffer' => $solicitation->counteroffer,
                'amount_approved' => $solicitation->amount_approved,
                'tax' => $solicitation->tax,
                'total' => $solicitation->total,
                'status' => $solicitation->status
            ]);

            $client->backups()->create([
                'debit' => $client->debit,
                'status' => $client->status
            ]);
        });
    }
}
