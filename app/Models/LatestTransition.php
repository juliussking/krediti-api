<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LatestTransition extends Model
{
    /** @use HasFactory<\Database\Factories\LatestTransitionFactory> */
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function liberation()
    {
        return $this->belongsTo(Liberation::class);
    }

    public function solicitation()
    {
        return $this->belongsTo(Solicitation::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
