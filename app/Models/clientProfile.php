<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clientProfile extends Model
{
    /** @use HasFactory<\Database\Factories\ClientProfileFactory> */
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
