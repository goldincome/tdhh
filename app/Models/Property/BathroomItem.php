<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BathroomItem extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];
}
