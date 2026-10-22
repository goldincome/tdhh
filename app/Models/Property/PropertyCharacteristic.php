<?php

namespace App\Models\Property;

use App\Models\Characteristic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PropertyCharacteristic extends Model
{
    use HasFactory;

    protected $fillable = ['property_id', 'characteristic_id'];

    /**
     * Get the characteristics that owns the PropertyCharacteristic
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function characteristic(): BelongsTo
    {
        return $this->belongsTo(Characteristic::class, );
    }
}
