<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Eatery;
use App\Models\Review;

class HomeController extends Controller
{
    public function __invoke()
    {
        $topEateries = Eatery::query()
            ->with(['district', 'features'])
            ->whereHas('reviews')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->limit(3)
            ->get();

        return view('home', [
            'eateryCount' => Eatery::count(),
            'districtCount' => District::count(),
            'reviewCount' => Review::count(),
            'topEateries' => $topEateries,
        ]);
    }
}
