<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Exceptions\AdminEmailAlreadyExistsException;
use App\Exceptions\CnpjAlreadyExistsException;
use App\Exceptions\CompanyEmailAlreadyExistsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRegisterRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Email;

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
        
        $user->profile()->create([ 
            'phone' => $input['admin_phone'],
            'birthday' => $input['admin_birthday'],
        ]);

        $company->admin_id = $user->id;

        
        $company->save();
        $user->save();

        UserRegistered::dispatch($user);
        
        return response()->json([
            'message' => 'Empresa cadastrada com sucesso',
        ]);

    }
}
