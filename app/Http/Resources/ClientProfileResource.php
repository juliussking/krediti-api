<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->profile?->avatar,
            'gender' => $this->profile?->gender,
            'marital_status' => $this->profile?->marital_status,
            'email' => $this->email,
            'person_type' => $this->person_type,
            'status' => $this->status,
            'birth_date' => $this->profile?->birth_date,
            'phone' => $this->profile?->phone,
            'debit' => $this->debit,
            'street' => $this->address?->street,
            'city' => $this->address?->city,
            'neighbor' => $this->address?->neighbor,
            'number' => $this->address?->number,
            'reference_point' => $this->address?->reference_point,
            'zipcode' => $this->address?->zipcode,
            'office_name' => $this->office?->name,
            'office_zipcode' => $this->office?->zipcode,
            'office_phone' => $this->office?->phone,
            'office_city' => $this->office?->city,
            'office_street' => $this->office?->street,
            'office_neighbor' => $this->office?->neighbor,
            'office_number' => $this->office?->number,
            'role' => $this->office?->role,
            'salary' => $this->office?->salary,
            'payment_date' => $this->office?->payment_date,
            'admission_date' => $this->office?->admission_date,
            'register_date' => $this->created_at,
            'solicitations' => SolicitationResource::collection($this->solicitations),
            'payments' => PaymentResource::collection($this->payments),
            

        ];
    }
}
