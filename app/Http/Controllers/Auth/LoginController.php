<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Auth\InvalidAuthenticationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use InvalidArgumentException;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        $input = $request->validated();

        if (!auth()->attempt($input)) {

           throw new InvalidAuthenticationException();
        }

        request()->session()->regenerate();

        $user = auth()->user()->load('profile');

        return new UserResource($user);
    }
}
