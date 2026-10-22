<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin  extends Authenticatable
{
    use HasFactory, UsesUuid;

    const SUPER_ADMIN = 1;
    const FINANCIAL_ADMIN = 2;
    const GENERAL_ADMIN = 3;
    const CUSTOMER_SUPPORT = 4;


    protected $fillable = [
        'name', 'email',
    ];
}

