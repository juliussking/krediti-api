<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientBackup extends Model
{
       protected $guarded = [
        'id',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
