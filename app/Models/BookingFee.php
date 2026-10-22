<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingFee extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'booking_amount', 'cleaning_fee', 'service_fee','discount_amount', 'vat'];
}
