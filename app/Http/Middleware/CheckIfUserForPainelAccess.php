<?php

namespace App\Http\Middleware;

use App\Exceptions\Auth\InvalidAuthenticationException;
use App\Exceptions\UserDoesNotHaveAccessToThePainelException;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfUserForPainelAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $email = $request->input('email');

        $user = User::whereEmail($email)->first();

        if (!$user) {

            throw new InvalidAuthenticationException();

        } 

        if (!$user->can("acesso painel")) {
            throw new UserDoesNotHaveAccessToThePainelException();
        }


        return $next($request);
    }
}
