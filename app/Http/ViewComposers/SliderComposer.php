<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
// use Illuminate\Pagination\Paginator;
use App\Models\Slider;
use Carbon\Carbon;

class SliderComposer
{
    public function compose(View $view)
    {
        $slider = Slider::where(function ($q) {
                            $curr_date = date('Y-m-d');
                            $q->where('date', '<=', $curr_date)
                                ->orWhereNull('date');
                        })
                        ->where(function ($q) {
                            $curr_date = date('Y-m-d');
                            $q->where('date_end', '>', $curr_date)
                                ->orWhereNull('date_end');
                        })
                        ->orderBy('sort', 'asc')
                        ->get();

        return $view->with('slider', $slider);
    }
}
