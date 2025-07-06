<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __invoke()
    {
        $company = Auth()->user()->company;

    $monthly = $company->newSubscription('default', 'price_1RguiRPmixXaMy1rbZ6zzEmL')
    ->checkout([
        'success_url' => 'https://example.com/success',
        'cancel_url' => 'https://example.com/cancel',
    ]);

    $yearly = $company->newSubscription('default', 'price_1RgsZXPmixXaMy1rF27agI6T')
    ->checkout([
        'success_url' => 'https://example.com/success',
        'cancel_url' => 'https://example.com/cancel',
    ]);



    return [
        'monthly_url' => $monthly->url,
        'yearly_url' => $yearly->url,
    ];
    }
}
