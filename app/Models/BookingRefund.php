<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRefund extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'reservation_id', 'refund_amount', 'cancellation_fee', 'percentage_charged',
        'reason_message', 'requested_by', 'approved_by', 'refund_status', 'is_refund_approved', 'is_refunded'
    ];
}
