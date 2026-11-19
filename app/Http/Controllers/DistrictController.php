<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\View\View;

class DistrictController extends Controller
{
    public function index(): View
    {
        $districts = District::withCount('eateries')->orderBy('name')->get();

        return view('districts.index', compact('districts'));
    }

    public function show(District $district): View
    {
        $district->load([
            'eateries' => fn ($query) => $query
                ->with(['district', 'features'])
                ->withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->orderBy('name'),
        ]);
        $reviewCount = $district->reviews()->count();

        return view('districts.show', compact('district', 'reviewCount'));
    }
}
