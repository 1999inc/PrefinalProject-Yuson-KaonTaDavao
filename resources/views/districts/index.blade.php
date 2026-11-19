@extends('layouts.app')

@section('title', "Districts · Kaon Ta! Davao")

@section('content')
    <p class="text-sm font-semibold uppercase tracking-widest text-orange-700">Explore by neighborhood</p>
    <h1 class="mt-1 text-3xl font-bold">Davao Districts</h1>
    <div class="mt-7 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($districts as $district)
            <a href="{{ route('districts.show', $district) }}" class="rounded-2xl border border-amber-200 bg-white p-6 hover:border-orange-300 hover:shadow-sm">
                <h2 class="text-xl font-bold">{{ $district->name }}</h2>
                <p class="mt-2 text-sm text-stone-600">{{ $district->eateries_count }} {{ $district->eateries_count === 1 ? 'eatery' : 'eateries' }}</p>
            </a>
        @endforeach
    </div>
@endsection
