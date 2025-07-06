<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAddress extends Model
{
    /** @use HasFactory<\Database\Factories\ClientAddressFactory> */
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
