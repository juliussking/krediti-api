<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory, Billable;

    protected $guarded = [
        'id'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function solicitations()
    {
        return $this->hasMany(Solicitation::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function liberations()
    {
        return $this->hasMany(Liberation::class);
    }

    public function stripeName()
    {
        return $this->fantasy_name;
    }
}
