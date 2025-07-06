<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientOffice extends Model
{
    /** @use HasFactory<\Database\Factories\ClientOfficeFactory> */
    use HasFactory;

    protected $guarded = [
        'id'
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
