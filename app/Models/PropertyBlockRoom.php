<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyBlockRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'status',
        'rate_price',
        'date',
        'blocked_by',
    ];

    public function getBlockedDaysAttribute()
    {
        return json_decode($this->affected_days);
    }
}
