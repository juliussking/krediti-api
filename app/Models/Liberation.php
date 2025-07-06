<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liberation extends Model
{
    /** @use HasFactory<\Database\Factories\LiberationFactory> */
    use HasFactory;

        protected $guarded = [
        'id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function solicitation()
    {
        return $this->belongsTo(Solicitation::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
