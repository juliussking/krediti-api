<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SolicitationResource extends JsonResource
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
            'client_id' => $this->client_id,
            'user_name' => $this->user->name,
            'amount_requested' => $this->amount_requested,
            'counteroffer' => $this->counteroffer,
            'amount_approved' => $this->amount_approved,
            'tax' => $this->tax,
            'total' => $this->total,
            'status' => $this->status,
            'solicitation_date' => $this->created_at,
        ];
    }
}
