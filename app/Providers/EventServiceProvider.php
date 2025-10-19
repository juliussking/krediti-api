<?php

namespace App\Providers;

use App\Events\ApproveSolicitation;
use App\Events\PaymentCreated;
use App\Events\UserRegistered;
use App\Listeners\CreateLiberationAfterApproveSolicitation;
use App\Listeners\SendWelcomeEmail;
use App\Listeners\UpdateClientAndLiberationStatus;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        UserRegistered::class => [
            SendWelcomeEmail::class,
        ],
        ApproveSolicitation::class => [
            CreateLiberationAfterApproveSolicitation::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
