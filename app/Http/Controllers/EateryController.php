<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEateryRequest;
use App\Http\Requests\UpdateEateryRequest;
use App\Models\District;
use App\Models\Eatery;
use App\Models\Feature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EateryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Eatery::query()
            ->with(['district', 'features'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderBy('name');

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('specialty', 'like', "%{$search}%");
            });
        }

        if ($request->filled('district')) {
            $query->where('district_id', $request->input('district'));
        }

        if ($request->filled('feature')) {
            $feature = $request->input('feature');
            $query->whereHas('features', fn ($builder) => $builder->where('name', $feature));
        }

        return view('eateries.index', [
            'eateries' => $query->paginate(6)->withQueryString(),
            'districts' => District::orderBy('name')->get(),
            'features' => Feature::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('eateries.create', [
            'districts' => District::orderBy('name')->get(),
            'features' => Feature::orderBy('name')->get(),
        ]);
    }

    public function store(StoreEateryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $featureIds = $data['features'];
        unset($data['features']);

        $permit = [
            'permit_no' => $data['permit_no'],
            'expires_on' => $data['expires_on'],
        ];
        unset($data['permit_no'], $data['expires_on']);

        $data['slug'] = Str::slug($data['name']);
        $data['is_open'] = $data['is_open'] ?? false;

        $eatery = Eatery::create($data);
        $eatery->permit()->create($permit);
        $eatery->features()->sync($featureIds);

        return redirect()->route('eateries.show', $eatery)->with('success', 'Eatery added.');
    }

    public function show(Eatery $eatery): View
    {
        $eatery->load([
            'district',
            'permit',
            'dishes',
            'features',
            'reviews' => fn ($query) => $query->with('user')->latest(),
        ]);
        $users = \App\Models\User::orderBy('name')->get();

        return view('eateries.show', compact('eatery', 'users'));
    }

    public function edit(Eatery $eatery): View
    {
        $eatery->load(['permit', 'features']);

        return view('eateries.edit', [
            'eatery' => $eatery,
            'districts' => District::orderBy('name')->get(),
            'features' => Feature::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateEateryRequest $request, Eatery $eatery): RedirectResponse
    {
        $data = $request->validated();
        $featureIds = $data['features'];
        unset($data['features']);

        $permit = [
            'permit_no' => $data['permit_no'],
            'expires_on' => $data['expires_on'],
        ];
        unset($data['permit_no'], $data['expires_on']);

        $data['slug'] = Str::slug($data['name']);
        $data['is_open'] = $data['is_open'] ?? false;

        $eatery->update($data);
        $eatery->permit()->updateOrCreate([], $permit);
        $eatery->features()->sync($featureIds);

        return redirect()->route('eateries.show', $eatery)->with('success', 'Eatery updated.');
    }

    public function destroy(Eatery $eatery): RedirectResponse
    {
        $eatery->permit()->delete();
        $eatery->dishes()->delete();
        $eatery->reviews()->delete();
        $eatery->features()->detach();
        $eatery->delete();

        return redirect()->route('eateries.index')->with('success', 'Eatery deleted.');
    }
}
