<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'avatar' => $this->avatar,
            'birth_date' => Carbon::parse($this->birth_date)->format('d/m/Y'),
            'gender' => $this->gender,
            'phone' => $this->phone,
            'marital_status' => $this->marital_status,
        ];
    }
}
