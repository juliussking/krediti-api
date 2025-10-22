<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /** @use HasFactory<\Database\Factories\ClientFactory> */
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function profile()
    {
        return $this->hasOne(clientProfile::class);
    }

    public function address()
    {
        return $this->hasOne(ClientAddress::class);
    }

    public function document()
    {
        return $this->hasOne(ClientDocument::class);
    }

    public function referenceContacts()
    {
        return $this->hasMany(ClientReferenceContact::class);
    }

    public function office()
    {
        return $this->hasOne(ClientOffice::class);
    }

    public function liberations()
    {
        return $this->hasMany(Liberation::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function solicitations()
    {
        return $this->hasMany(Solicitation::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function backups()
    {
        return $this->hasMany(ClientBackup::class);
    }

}
