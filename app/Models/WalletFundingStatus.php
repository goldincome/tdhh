<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletFundingStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color' ];

    const PROCESSING = 2;
    const APPROVED = 1;
    const REJECTED = 3;
    const SUBMITTED = 4;
    const CANCELLED = 5;
    const REFUND_REQUESTED = 6;
    const REFUNDED = 7;
    const REFUND_REJECTED = 8;
}
