@extends('layouts.app')

@section('title', "Eateries · Kaon Ta! Davao")

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-widest text-orange-700">Find your next favorite</p>
            <h1 class="mt-1 text-3xl font-bold">Davao Eateries</h1>
        </div>
        <a href="{{ route('eateries.create') }}" class="rounded-full bg-orange-700 px-5 py-3 font-semibold text-white hover:bg-orange-800">Add an eatery</a>
    </div>

    <form method="GET" action="{{ route('eateries.index') }}" class="mt-7 grid gap-3 rounded-2xl border border-amber-200 bg-white p-4 sm:grid-cols-2 lg:grid-cols-4">
        <label class="text-sm font-medium text-stone-700">
            Search name or specialty
            <input type="search" name="q" value="{{ request('q') }}" class="mt-1 w-full rounded-lg border-stone-300 focus:border-orange-600 focus:ring-orange-600">
        </label>
        <label class="text-sm font-medium text-stone-700">
            District
            <select name="district" class="mt-1 w-full rounded-lg border-stone-300 focus:border-orange-600 focus:ring-orange-600">
                <option value="">All districts</option>
                @foreach ($districts as $district)
                    <option value="{{ $district->id }}" @selected(request('district') == $district->id)>{{ $district->name }}</option>
                @endforeach
            </select>
        </label>
        <label class="text-sm font-medium text-stone-700">
            Feature
            <select name="feature" class="mt-1 w-full rounded-lg border-stone-300 focus:border-orange-600 focus:ring-orange-600">
                <option value="">All features</option>
                @foreach ($features as $feature)
                    <option value="{{ $feature->name }}" @selected(request('feature') == $feature->name)>{{ $feature->name }}</option>
                @endforeach
            </select>
        </label>
        <div class="flex items-end gap-3">
            <button type="submit" class="rounded-lg bg-orange-700 px-5 py-2.5 font-semibold text-white hover:bg-orange-800">Filter</button>
            <a href="{{ route('eateries.index') }}" class="px-2 py-2.5 font-medium text-stone-600 hover:text-orange-800">Clear</a>
        </div>
    </form>

    @if ($eateries->isEmpty())
        <p class="mt-8 rounded-2xl border border-dashed border-amber-300 bg-white p-8 text-center text-stone-600">No eateries match your filters.</p>
    @else
        <div class="mt-8 grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($eateries as $eatery)
                <x-eatery-card :eatery="$eatery" />
            @endforeach
        </div>
        <div class="mt-8">{{ $eateries->links() }}</div>
    @endif
@endsection
