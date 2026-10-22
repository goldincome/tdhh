<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerCommissionStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color' ];

    const IN_PROGRESS = 2;
    const PAID = 1;
    const CANCELLED = 3;
    
}
