<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyRule extends Model
{
    use HasFactory;

    protected $fillable = ['property_id', 'rule_id'];

    public function rule(): BelongsTo
    {
        return $this->belongsTo(HouseRule::class);
    }
}
