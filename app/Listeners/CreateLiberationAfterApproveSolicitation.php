<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreateLiberationAfterApproveSolicitation
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
        $solicitation = $event->solicitation;

        $solicitation->liberation()->create([
            'user_id' => Auth()->user()->id,
            'client_id' => $solicitation->client_id,
            'amount' => $solicitation->amount_approved,
            'status' => 'Ativo',
            'expiration_date' => now()->addDays(30),
            'company_id' => Auth()->user()->company_id

        ]);
    }
}
