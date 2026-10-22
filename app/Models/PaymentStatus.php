<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color'];

    const PAID = 1;
    const CANCELLED_BY_CUSTOMER = 2;
    const CANCELLED_BY_PARTNER = 3;
    const CANCELLED_BY_ADMIN = 4;
    const REFUND_REQUESTED = 6;
    const REFUNDED = 7;

    // public function reservations()
    // {
    //     return $this->belongsToMany(Product::class)
    //         ->withPivot('products_amount', 'price')
    //         ->withTimestamps();
    // }

    public function reservations(){
        return $this->belongsToMany(Reservations::class , 'payment_status_reservation' ,
        'payment_status_id', 'reservation_id' )
         ->withPivot('description')
         ->withTimestamps();
    }
}
