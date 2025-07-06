<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientOfficeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'client_id' => $this->client_id,
            'name' => $this->name,
            'phone' => $this->phone,
            'zipcode' => $this->zipcode,
            'street' => $this->street,
            'city' => $this->city,
            'neighbor' => $this->neighbor,
            'number' => $this->number,
            'role' => $this->role,
            'salary' => $this->salary,
            'payment_date' => Carbon::parse($this->payment_date)->format('d/m/Y'),
            'admission_date' => Carbon::parse($this->admission_date)->format('d/m/Y'),
        ];
    }
}
