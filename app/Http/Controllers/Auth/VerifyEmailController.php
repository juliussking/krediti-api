<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\EmailAlreadyVerifiedException;
use App\Exceptions\InvalidVerifyEmailTokenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyEmailRequest;
use App\Models\User;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function __invoke(VerifyEmailRequest $request)
    {
        $input = $request->validated();

        $user = User::whereToken($input['token'])->first();

        if (!$user) {
            
            throw new InvalidVerifyEmailTokenException();

        }

        if($user->email_verified_at) {

            throw new EmailAlreadyVerifiedException();

        }

        $user->email_verified_at = now();
        $user->save();

        return response()->json([
            'message' => 'Email verificado com sucesso!',
        ]);

    }
}
