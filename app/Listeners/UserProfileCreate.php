<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserProfileCreate
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
        $user = $event->user;

        $user->profile()->create([
            'phone' => $event->input['admin_phone'],
            'birthday' => $event->input['admin_birthday'],
        ]);
    }
}
