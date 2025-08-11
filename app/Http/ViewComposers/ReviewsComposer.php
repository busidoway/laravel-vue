<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Review;

class ReviewsComposer
{
    public function compose(View $view)
    {
        // $curr_date = date('Y-m-d');

        $reviews = [];

        $reviews['slider'] = Review::limit(10)->get();

        return $view->with('reviews', $reviews);
    }
}
