<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiberationBackup extends Model
{
    protected $guarded = [
        'id',
    ];
    public function liberation()
    {
        return $this->belongsTo(Liberation::class);
    }
}
