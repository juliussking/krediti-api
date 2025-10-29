<?php

namespace App\Http\Controllers\Auth;

use App\Events\CompanyRegistered;
use App\Exceptions\AdminEmailAlreadyExistsException;
use App\Exceptions\CnpjAlreadyExistsException;
use App\Exceptions\CompanyEmailAlreadyExistsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRegisterRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CompanyRegisterController extends Controller
{
    public function __invoke(CompanyRegisterRequest $request)
    {
        $input = $request->validated();

        $admin_email = User::whereEmail($input['admin_email'])->exists();

        if ($admin_email) {

            throw new AdminEmailAlreadyExistsException();
        }

        $company_email = Company::whereEmail($input['company_email'])->exists();

        if ($company_email) {

            throw new CompanyEmailAlreadyExistsException();
        }

        $companies = Company::whereCnpj($input['cnpj'])->exists();

        if ($companies) {

            throw new CnpjAlreadyExistsException();
        }

        [$company, $user] = DB::transaction(function () use ($input) {

            $company = Company::create([
                'social_reason' => $input['social_reason'],
                'fantasy_name' => $input['fantasy_name'],
                'cnpj' => $input['cnpj'],
                'phone' => $input['company_phone'],
                'email' => $input['company_email'],
                'status' => 'Pendente',
            ]);

            $user = User::create([
                'name' => $input['admin_name'],
                'email' => $input['admin_email'],
                'password' => bcrypt($input['password']),
                'company_id' => $company->id,
                'token' => Str::uuid(),
            ]);

            $user->assignRole('admin');

            return [$company,$user];
        });

        $company->admin_id = $user->id;

        $company->save();
        $user->save();

        CompanyRegistered::dispatch(
            $user,
            $company,
            ['admin_phone' => $input['admin_phone'], 'admin_birthday' => $input['admin_birthday']]
        );

        return response()->json([
            'msg' => 'CompanyRegisterSuccess',
        ]);
    }
}
