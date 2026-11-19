@extends('layouts.app')

@section('title', "Kaon Ta! Davao – Yuson's Food Guide")

@section('content')
    <p class="text-sm font-semibold uppercase tracking-widest text-orange-700">Local food lover</p>
    <h1 class="mt-1 text-3xl font-bold">{{ $user->name }}</h1>
    <p class="mt-2 text-stone-600">{{ $user->reviews->count() }} {{ $user->reviews->count() === 1 ? 'review written' : 'reviews written' }}</p>

    @if ($user->reviews->isEmpty())
        <p class="mt-7 rounded-2xl border border-dashed border-amber-300 bg-white p-8 text-stone-600">No reviews written yet.</p>
    @else
        <div class="mt-7 space-y-4">
            @foreach ($user->reviews as $review)
                <article class="rounded-2xl border border-amber-200 bg-white p-5">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <h2 class="text-lg font-bold">
                            <a href="{{ route('eateries.show', $review->eatery) }}" class="text-orange-800 hover:underline">{{ $review->eatery->name }}</a>
                        </h2>
                        <x-rating :rating="$review->rating" />
                    </div>
                    <p class="mt-1 text-xs text-stone-500">{{ $review->created_at->format('F j, Y') }}</p>
                    <p class="mt-4 whitespace-pre-line text-stone-700">{{ $review->body }}</p>
                </article>
            @endforeach
        </div>
    @endif
@endsection
