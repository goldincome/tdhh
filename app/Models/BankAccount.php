<?php

namespace App\Models;

use App\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = ['country_id', 'bank_name', 'account_name', 'account_number', 'currency_code', 'bank_logo', 'routing_number', 'swift_code', 'status'];

    public function country(){
        return $this->belongsTo(Country::class);
    }
}
