<?php

namespace App\Http\Middleware;

use App\Exceptions\UserNotAuthorizedException;
use App\Exceptions\UserNotFoundException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission): Response
    {
        $user = $request->user();

        if (!$user) {
            throw new UserNotFoundException();
        }

        if (!$user->can($permission)) {

            throw new UserNotAuthorizedException();
        }

        return $next($request);
    }
}
