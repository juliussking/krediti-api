<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show()
    {
        $users = User::where('company_id', auth()->user()->company_id)->get();

        return $users;
    }
}
