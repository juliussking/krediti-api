<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientDocument extends Model
{
    /** @use HasFactory<\Database\Factories\ClientDocumentFactory> */
    use HasFactory;

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
