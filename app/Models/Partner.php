<?php

namespace App\Models;

use App\Traits\UsesUuid;
use App\Models\Property\Property;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Partner extends Authenticatable
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'first_name',
        'last_name',
        'business_name',
        'email',
        'password',
        'profile_url',
        'phone',
        'partner_type',
        'parent_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getFullNameAttribute(){
        return "$this->first_name $this->last_name";
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'listed_by');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservations::class);
    }

    public function recentReservations(): HasMany
    {
        return $this->reservations()->latest()->limit(5);
    }
    
    public function partner_commissions(): HasMany
    {
        return $this->hasMany(PartnerCommission::class);
    }
}