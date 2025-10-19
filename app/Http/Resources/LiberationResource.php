<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LiberationResource extends JsonResource
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
            'user_name' => $this->user->name,
            'client_name' => $this->client->name,
            'amount' => $this->amount,
            'status' => $this->status,
            'expiration_date' => $this->expiration_date,
            'liberation_date' => $this->created_at
        ];
    }
}
