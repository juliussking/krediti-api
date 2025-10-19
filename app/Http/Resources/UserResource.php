<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'company_status' => $this->company?->subscription('Krediti')?->asStripeSubscription()?->status,
            'company_hasSubscription' => !!$this->company?->subscribed('Krediti'),
            'avatar' => $this->profile?->avatar,

            
        ];
    }
}
