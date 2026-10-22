<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarSync extends Model
{
    use HasFactory;

    protected $fillable = ['property_id', 'calendar_name', 'calendar_url', 'last_sync'];
}
