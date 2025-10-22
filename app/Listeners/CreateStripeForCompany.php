<?php

namespace App\Listeners;

use App\Models\Plan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateStripeForCompany
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
        $company = $event->company;
        $user = $event->user;

        $company->admin_id = $user->id;

        $company->createOrGetStripeCustomer([
            'name'  => $user->name,
            'email' => $user->email
        ]);

        $plan = Plan::findOrFail(1);

        $stripePriceId = $plan->stripe_price_monthly_id;

        $company->newSubscription($plan->name, $stripePriceId)
            ->trialDays(30)
            ->create();

        $company->save();
        $user->save();

    }
}
