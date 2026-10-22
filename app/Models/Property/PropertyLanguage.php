<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyLanguage extends Model
{
    use HasFactory;

    protected $fillable = ['property_id', 'language_id'];
}
