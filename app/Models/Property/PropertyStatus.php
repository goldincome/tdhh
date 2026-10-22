<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    const PENDING = 1;
    const AVAILABLE = 2;
    const SUSPENDED_BY_PARTNER = 3;
    const SUSPENDED_BY_ADMIN = 4;
    const DISABLED = 6;
}
