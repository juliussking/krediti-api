<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fantasy_name' => ['required', 'string', 'max:50'],
            'social_reason' => ['required', 'string', 'max:50'],
            'cnpj' => ['required', 'string', 'max:20'],
            'company_phone' => ['required', 'string', 'max:20'],
            'company_email' => ['required', 'email', 'max:30'],
            'admin_name' => ['required', 'string', 'max:40'],
            'admin_phone' => ['required', 'string', 'max:20'],
            'admin_birthday' => ['required', 'date'],
            'admin_email' => ['required', 'email', 'max:30'],
            'password' => ['required', 'string'],
        ];
    }
}
