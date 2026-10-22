<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyBathroomItem extends Model
{
    use HasFactory;

    protected $fillable = ['property_id', 'item_id'];
}
