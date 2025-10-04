<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UsersStatisticsResource;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show()
    {
        $users = User::where('company_id', auth()->user()->company_id)->paginate(10);

        return [
            'users' => UsersStatisticsResource::collection($users),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ];
    }

    public function statistics()
    {
        $user = User::where('company_id', Auth()->user()->company_id)->get();
        return [

            'user_count' => $user->count(),
            'admin_count' => $user->where('status', 'Ativo')->count(),
            'gerent_count' => $user->where('status', 'Vencido')->count(),
            'clients_paid_off' => $user->where('status', 'Quitado')->count(),
        ];
    }

    public function store(UserRegisterRequest $request)
    {
        $input = $request->validated();

        $user = User::create([
            'token' => Str::uuid(),
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => bcrypt($input['password']),
            'company_id' => auth()->user()->company_id,
        ]);

        $user->profile()->create([
            'user_id' => $user->id,
            'birthday' => $input['birthday'],
            'phone' => $input['phone'],
        ]);

        $user->save();
        UserRegistered::dispatch($user);
        
        return $user;

    }
}
