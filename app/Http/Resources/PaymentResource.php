<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'client_id' => $this->client_id,
            'liberation_id' => $this->liberation_id,
            'amount' => $this->amount,
            'total' => $this->total,
            'client_debit' => $this->client->debit,
            'payment_type' => $this->payment_type,
            'payment_date' => $this->created_at,
        ];
    }
}
