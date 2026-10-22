<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FrontPageCity extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['state_id', 'status', 'image'];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function getCoverImageAttribute()
    {
        return $this->getMedia('fpc_image')->first() ?
            $this->getMedia('fpc_image')->first()->getUrl() :
            null;
    }
}

