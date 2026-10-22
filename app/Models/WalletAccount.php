<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WalletAccount extends Model
{
    use HasFactory,  UsesUuid;

    const CREDIT_TRANX = 'credit';
    const DEBIT_TRANX = 'debit';
    protected $fillable = [
        'user_id','credit','balance','debit', 'description'
    ];


    public function wallet_funding(): BelongsTo
    {
        return $this->belongsTo(WalletFunding::class, 'wallet_funding_id');
    }
}
