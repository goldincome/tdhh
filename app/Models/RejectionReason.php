<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectionReason extends Model
{
    use HasFactory;

    protected $fillable = ['model_id', 'model_name', 'reason'];

    const MODEL_PARTNER = 1;
    const MODEL_PROPERTY = 2;
    const MODEL_CUSTOMER = 3;
}
