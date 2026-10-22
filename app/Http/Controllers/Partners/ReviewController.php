<?php

namespace App\Http\Controllers\Partners;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Property\Property;

class ReviewController extends Controller
{
    public function reviewList($id)
    {
        $user = $this->loggedInUser('partner');
        $property = Property::where('id', $id)->with('reviews')->first();

        return view('partners.account.property.reviews', compact('property'));
    }
}
