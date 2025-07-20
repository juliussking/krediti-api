<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Auth\InvalidAuthenticationException;
use App\Exceptions\CompanyDontHasSubscriptionException;
use App\Exceptions\CompanyNotFoundException;
use App\Exceptions\EmailIsNotVerifiedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        $input = $request->validated();

        if (!auth()->attempt($input)) {

            throw new InvalidAuthenticationException();
        }

        $user = auth()->user();

        if (!$user->hasVerifiedEmail()) {

            Auth()->logout();

            throw new EmailIsNotVerifiedException();
        }

        $company = $user->company;

        if (!$company) {
            Auth()->logout();

            throw new CompanyNotFoundException();
        }

        if (!$company->subscribed('Krediti') && $company->admin_id !== $user->id) {

            Auth()->logout();
            throw new CompanyDontHasSubscriptionException();
        }

        request()->session()->regenerate();

        $user->load('profile');


        $subscription = $company->subscription('Krediti');

        $stripe = $subscription->asStripeSubscription();

        return new UserResource($user);
    }
}
