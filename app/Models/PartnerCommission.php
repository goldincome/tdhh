<?php

namespace App\Models;

use App\Traits\UsesUuid;
use App\Models\Reservations;
use App\Models\PartnerCommissionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PartnerCommission extends Model
{
    use UsesUuid;
    use HasFactory;

    protected $fillable =[
        'partner_id', 'reservation_id', 'commission_amt',  'commission_percentage', 
        'commission_status_id', 'ref_no', 'payout_amt'
    ];

    const FROM_BOOKING = 1;
    const CANCELLED_BOOKING = 2;

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservations::class);
    }

    public function partner_commission_status(): BelongsTo
    {
        return $this->belongsTo(PartnerCommissionStatus::class, 'commission_status_id');
    }
}
