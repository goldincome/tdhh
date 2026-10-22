<?php

namespace App\Models;

use App;
use App\Traits\UsesUuid;
use Spatie\MediaLibrary\HasMedia;
use App\Models\WalletFundingStatus;
use App\Repositories\WalletAccounts\WalletAccountRepository;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\Container as ContainerContainer;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WalletFunding extends Model implements HasMedia
{
    use HasFactory, UsesUuid, InteractsWithMedia;

    protected $fillable = [
        'bank_account_id', 'user_id','amount_paid', 'date_paid','depositor_name', 'teller_image',
        'wallet_funding_status_id', 'is_refund','options','currency_code', 'converted_amount',
        'converted_currency_code', 'account_number', 'bank_name', 'first_approval', 'final_approval'
    ];

    protected $casts = [
        'date_paid' => 'datetime',
    ];


    public function getTellerImageAttribute(){
        return $this->getMedia('teller_image')->first();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function wallet_funding_status(): BelongsTo
    {
        return $this->belongsTo(WalletFundingStatus::class, 'wallet_funding_status_id');
    }

    public function getOtherDetailsAttribute()
    {
        $otherDetails = json_decode($this->options);
        return $otherDetails;
    }
    public function getOtherDetailsToArrayAttribute()
    {
        $otherDetails = json_decode($this->options, true);
        return $otherDetails;
    }
    public function wallet_account(): HasOne
    {
        return $this->hasOne(WalletAccount::class, 'wallet_funding_id');
    }

    public function getCanRequestRefundAttribute(float $balance)
    {
        if($this->converted_amount > $balance){
            return false;
        }
        if($this->wallet_funding_status_id == WalletFundingStatus::REFUND_REQUESTED
        || $this->wallet_funding_status_id == WalletFundingStatus::CANCELLED
        || $this->wallet_funding_status_id == WalletFundingStatus::SUBMITTED
        || $this->wallet_funding_status_id == WalletFundingStatus::APPROVED
        || $this->wallet_funding_status_id == WalletFundingStatus::REJECTED)
        {
            return true;
        }
       
        return false;
    }
}
