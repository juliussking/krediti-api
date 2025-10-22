<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterClientRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:50'], //*
            'identity' => ['required','string', 'max:255'], //*
            'phone' => ['required','string', 'max:255'], //*
            'marital_status' => ['string', 'max:255'],
            'email' => ['string', 'email', 'max:255'],
            'zipcode' => ['string', 'max:255'],
            'street' => ['required','string', 'max:255'], //*
            'city' => ['string', 'max:255'],
            'neighbor' => ['string', 'max:255'],
            'client_number' => ['integer'],
            'reference_point' => ['required','string', 'max:255'], //*
            'birth_date' => ['required','date', 'max:255'], //*
            'gender' => ['string', 'max:255'],

            'person_type' => ['string', 'max:255'],
            'cpf' => ['required','string', 'max:255'], //*
            'cnpj' => ['max:255'],

            'office_name' => ['string', 'max:255'],
            'office_zipcode' => ['string', 'max:255'],
            'office_street' => ['string', 'max:255'],
            'office_neighbor' => ['string', 'max:255'],
            'office_phone' => ['string', 'max:255'],
            'office_city' => ['string', 'max:255'],
            'office_number' => ['integer'],
            'office_cnpj' => ['max:255'],
            'office_role' => ['required','string', 'max:255'], //*
            'office_salary' => ['required', 'max:255'], //*
            'office_payment_date' => ['required','date', 'max:255'], //*
            'office_admission_date' => ['date', 'max:255'],

            'amount_requested' => ['required','string', 'max:255'], //*
            'tax' => ['required', 'max:255'], //*

            'reference_contacts' => ['required','array', 'max:255'], //*
            'reference_contacts.*.name' => ['required','string', 'max:255'], //*
            'reference_contacts.*.phone' => ['required','string', 'max:255'], //*
            'reference_contacts.*.relation' => ['required','string', 'max:255'], //*

            'observations' => ['required','string'], //*

        ];
    }
}
