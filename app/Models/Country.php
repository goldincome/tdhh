<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    const NIGERIA = 160;

    const UNITED_KINGDOM = 230;

    const UNITED_STATE = 231;

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }
}
