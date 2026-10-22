<?php

namespace App\Models\Property;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyParkingOption extends Model
{
    use HasFactory;

    const PARKING_LOCATION_ONSITE = 1;
    const PARKING_LOCATION_OFFSITE = 2;

    const PARKING_TYPE_PUBLIC = 1;
    const PARKING_TYPE_PRIVATE = 2;

    const PARKING_RESERVATION_ALLOWED = 1;
    const PARKING_RESERVATION_NOT_ALLOWED = 2;
}
