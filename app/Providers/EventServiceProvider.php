<?php

namespace App\Providers;

use App\Events\ApproveSolicitation;
use App\Events\BeforeApproveSolicitation;
use App\Events\BeforePayment;
use App\Events\PaymentCreated;
use App\Events\CompanyRegistered;
use App\Events\SolicitationRecused;
use App\Events\UserRegistered;
use App\Listeners\BackupClientFields;
use App\Listeners\BackupDataBeforeApproveSolicitation;
use App\Listeners\BackupFieldsBeforePayment;
use App\Listeners\CreateLiberationAfterApproveSolicitation;
use App\Listeners\CreateStripeForCompany;
use App\Listeners\RestoreFieldsAfterSolicitationRecused;
use App\Listeners\SendWelcomeEmail;
use App\Listeners\UpdateFieldsInClientAfterPayment;
use App\Listeners\UserProfileCreate;
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
        CompanyRegistered::class => [
            SendWelcomeEmail::class,
            UserProfileCreate::class,
            CreateStripeForCompany::class,  
        ],

        UserRegistered::class => [
            UserProfileCreate::class,  
            SendWelcomeEmail::class,
        ],

        BeforeApproveSolicitation::class => [
            BackupDataBeforeApproveSolicitation::class,
        ],

        ApproveSolicitation::class => [
            CreateLiberationAfterApproveSolicitation::class,
        ],

        SolicitationRecused::class => [
            RestoreFieldsAfterSolicitationRecused::class
        ],

        BeforePayment::class => [
            BackupFieldsBeforePayment::class,
        ],

        PaymentCreated::class => [
            UpdateFieldsInClientAfterPayment::class
        ],
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
