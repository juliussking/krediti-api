<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SubscriptionRequest;
use App\Models\Plan;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __invoke(SubscriptionRequest $request)
    {
        $input = $request->validated();

        $user = Auth()->user();
        $company = Auth()->user()->company;
        $company->createOrGetStripeCustomer([
            'name' => $user->name,
            'email' => $user->email
        ]);
        $plan = Plan::findOrFail($input['plan_id']);
        
        $stripePriceId = $plan->stripe_price_monthly_id;

        if($input['frequency'] == 'yearly') {
            $stripePriceId = $plan->stripe_price_yearly_id;
        }

    $subscription = $company->newSubscription($plan->name, $stripePriceId)
    ->checkout([
        'success_url' => config('app.portal_url') . '/sucesso-assinatura',
        'cancel_url' => config('app.portal_url') . '/cancelar-assinatura',
    ]);

    



    return [
        'subscription_url' => $subscription->url,
    ];
    }
}
