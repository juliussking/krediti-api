<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Exceptions\UserNotAuthorizedException;
use App\Filters\DateBetweenFilter;
use App\Filters\UserGlobalSearchFilter;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UsersStatisticsResource;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function show()
    {
        $users = QueryBuilder::for(User::class)
            ->where('company_id', Auth::user()->company_id)
            ->allowedFilters(
                AllowedFilter::custom('search', new UserGlobalSearchFilter()),
                AllowedFilter::custom('created_at', new DateBetweenFilter()),
            )
            ->paginate(10)
            ->appends(request()->query());

        return [
            'users' => UsersStatisticsResource::collection($users),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'links' => $users->toArray()['links'] ?? [],
            ],
        ];
    }

    public function statistics()
    {
        $user = User::where('company_id', Auth()->user()->company_id)->get();

        $totalUsers = $user->count();
        $usersActive = $user->where('status', 'Ativo')->count();
        $usersInactive = $user->where('status', 'Inactive')->count();
        $usersBlocked = $user->where('status', 'Blocked')->count();

        return [
            'meta' => [
                'users_count' => $totalUsers,
                'users_active' => $usersActive,
                'users_inactive' => $usersInactive,
                'users_blocked' => $usersBlocked
            ]
        ];
    }

    public function store(UserRegisterRequest $request)
    {
        $user = Auth()->user();

        $input = $request->validated();

        $user = DB::transaction(function () use ($input) {

            $user = User::create([
                'token' => Str::uuid(),
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => bcrypt($input['password']),
                'company_id' => auth()->user()->company_id,
            ]);

            return $user;
        });

        UserRegistered::dispatch(
            $user,
            ['phone' => $input['phone'], 'birthday' => $input['birthday']]
        );

        return response()->json([
            'msg' => 'UserRegisterSuccess',
        ]);
    }
}
