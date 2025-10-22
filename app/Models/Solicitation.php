<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitation extends Model
{
    /** @use HasFactory<\Database\Factories\SolicitationFactory> */
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function liberation()
    {
        return $this->hasOne(Liberation::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function backups()
    {
        return $this->hasMany(SolicitationBackup::class);
    }
}
