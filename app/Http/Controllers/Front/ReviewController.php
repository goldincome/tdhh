<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\RatingParameter;
use App\Models\Reservations;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{

    public function __construct(

    ) {
        $this->middleware('auth');
    }

    public function reviewList(){
        $user = $this->loggedInUser();
        $reviews = Review::where('user_id', $user->id)->get();
        return view('account.reviews.list', compact('reviews'));
    }

    public function reviewDetails($reviewId){

        $user = $this->loggedInUser();
        $review = Review::where(['id' => $reviewId, 'user_id' => $user->id])->firstOrFail();
        return view('account.reviews.details', compact('review'));
    }

    public function createReview(Request $request){
        $reservation = Reservations::findOrFail($request->id);
        $ratingParams = RatingParameter::where('status', true)->get();
        return view('account.bookings.review', compact('reservation', 'ratingParams'));
    }

    public function submitReview(Request $request){
        $ratings = [];
        $user = $this->loggedInUser();
        $avgRating = array_sum($request->param) / count($request->param);

        $review = Review::create([
            'reservation_id' => $request->id,
            'user_id' => $user->id,
            'review' => $request->review,
            'status' => true,
            'rating' => $avgRating,
        ]);

        foreach ($request->param as $paramId => $score) {
            $ratings[] = [
                'review_id' => $review->id,
                'param_id' => $paramId,
                'rating' => $score,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Rating::insert($ratings);

        $this->flashSuccessMessage('Thanks for the review');
        return redirect()->route('customer.review.list');
    }
}
