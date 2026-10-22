<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'status',
        'rate_price',
        'date',
    ];

    public function getAffectedRateDaysAttribute(){
        return json_decode($this->affected_days);
    }
}
