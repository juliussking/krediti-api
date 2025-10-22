<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitationBackup extends Model
{
       protected $guarded = [
        'id',
    ];
    public function solicitation()
    {
        return $this->belongsTo(Solicitation::class);
    }
}
