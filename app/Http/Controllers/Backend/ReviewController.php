<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function allReview(){
        $allReviews = Review::latest()->get();

        return view('admin.backend.review.all_review',compact('allReviews'));
    }
}
