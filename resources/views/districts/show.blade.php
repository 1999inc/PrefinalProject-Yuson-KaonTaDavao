@extends('layouts.app')

@section('title', "Kaon Ta! Davao – Yuson's Food Guide")

@section('content')
    <a href="{{ route('districts.index') }}" class="text-sm font-semibold text-orange-800 hover:underline">← All districts</a>
    <p class="mt-5 text-sm font-semibold uppercase tracking-widest text-orange-700">A taste of Davao</p>
    <h1 class="mt-1 text-3xl font-bold">{{ $district->name }}</h1>
    <p class="mt-2 text-stone-600">{{ $reviewCount }} {{ $reviewCount === 1 ? 'review' : 'reviews' }} in {{ $district->name }}</p>

    @if ($district->eateries->isEmpty())
        <p class="mt-7 rounded-2xl border border-dashed border-amber-300 bg-white p-8 text-stone-600">No eateries in this district yet.</p>
    @else
        <div class="mt-7 grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($district->eateries as $eatery)
                <x-eatery-card :eatery="$eatery" />
            @endforeach
        </div>
    @endif
@endsection
