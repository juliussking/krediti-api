<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlanResource;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $plans = Plan::all();

        return PlanResource::collection($plans);
    }
}
