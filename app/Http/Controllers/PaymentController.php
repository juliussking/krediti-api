<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentResource;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function show()
    {
        $payments = Payment::all();

        return [
            'payments' => PaymentResource::collection($payments),
        ];
    }
}
