<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Booking\BookingService;
use App\Repositories\Bookings\BookingsRepository;

class AdminBookingController extends Controller
{
    protected $bookingRepo;
    protected $bookingService;

    public function __construct(BookingsRepository $bookingRepo, BookingService $bookingService)
    {
        $this->bookingRepo = $bookingRepo;
        $this->bookingService = $bookingService;
    }

    public function show()
    {
        return $this->bookingRepo->all();
        
    }
}


