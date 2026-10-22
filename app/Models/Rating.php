<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = ['review_id', 'param_id', 'rating'];

    public function param(): BelongsTo
    {
        return $this->belongsTo(RatingParameter::class, 'param_id');
    }
}
