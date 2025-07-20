<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function show()
    {
        $company = Company::findOrFail(auth()->user()->company_id);

        $subscription = $company->subscription('Krediti');

        $stripe = $subscription->asStripeSubscription();
        

        if (!$company) {
            abort(404);
        }

        return [
            'company' => new CompanyResource($company),
            'due_date' => optional($subscription->asStripeSubscription())->current_period_end
                ? Carbon::createFromTimestamp($subscription->asStripeSubscription()->current_period_end)->toDateString() // retorna: 2025-09-13
                : null,
            'status' => $stripe->status,
            'period_start' => optional($subscription->asStripeSubscription())->current_period_start
                ? Carbon::createFromTimestamp($subscription->asStripeSubscription()->current_period_start)->toDateString() // retorna: 2025-09-13
                : null,
            'period_end' => optional($subscription->asStripeSubscription())->current_period_end
                ? Carbon::createFromTimestamp($subscription->asStripeSubscription()->current_period_end)->toDateString() // retorna: 2025-09-13
                : null,
            'plan' => $stripe->items->data[0]->price->nickname ?? 'sem nome',
            'price' => $stripe->items->data[0]->price->unit_amount / 100,
        ];
    }
}
