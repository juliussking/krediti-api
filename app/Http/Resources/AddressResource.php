<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'zipcode' => $this->zipcode,
            'street' => $this->street,
            'city' => $this->city,
            'neighbor' => $this->neighbor,
            'number' => $this->number,
            'reference_point' => $this->reference_point
        ];
    }
}
