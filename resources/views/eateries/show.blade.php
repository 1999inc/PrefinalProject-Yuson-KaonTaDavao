@extends('layouts.app')

@section('title', "Kaon Ta! Davao – Yuson's Food Guide")

@section('content')
    <div class="flex flex-wrap items-start justify-between gap-5">
        <div>
            <p class="text-sm font-semibold uppercase tracking-widest text-orange-700">{{ $eatery->district->name }} · {{ ucfirst($eatery->price_range) }}</p>
            <h1 class="mt-1 text-3xl font-bold sm:text-4xl">{{ $eatery->name }}</h1>
            <p class="mt-3 text-stone-600">{{ $eatery->address }}</p>
            @if ($eatery->specialty)
                <p class="mt-2 text-lg font-medium text-stone-800">Known for {{ $eatery->specialty }}</p>
            @endif
            @unless ($eatery->is_open)
                <span class="mt-3 inline-flex rounded-full bg-stone-100 px-3 py-1 text-sm font-semibold text-stone-700">Closed</span>
            @endunless
        </div>
        <div class="flex gap-2">
            <a href="{{ route('eateries.edit', $eatery) }}" class="rounded-full border border-orange-700 px-5 py-2.5 font-semibold text-orange-800 hover:bg-orange-50">Edit</a>
            <form method="POST" action="{{ route('eateries.destroy', $eatery) }}" onsubmit="return confirm('Delete this eatery?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-full border border-red-300 px-5 py-2.5 font-semibold text-red-700 hover:bg-red-50">Delete</button>
            </form>
        </div>
    </div>

    <div class="mt-6 flex flex-wrap gap-2">
        @foreach ($eatery->features as $feature)
            <a href="{{ route('eateries.index', ['feature' => $feature->name]) }}" class="rounded-full bg-orange-100 px-3 py-1.5 text-sm font-medium text-orange-900">{{ $feature->name }}</a>
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_2fr]">
        <section class="rounded-2xl border border-amber-200 bg-white p-6">
            <h2 class="text-xl font-bold">Business permit</h2>
            @if ($eatery->permit)
                <p class="mt-4 text-sm text-stone-500">Permit number</p>
                <p class="font-semibold">{{ $eatery->permit->permit_no }}</p>
                <p class="mt-3 text-sm text-stone-500">Expires</p>
                <p class="font-semibold">{{ $eatery->permit->expires_on->format('F j, Y') }}</p>
                @if ($eatery->permit->expires_on->isBefore(today()))
                    <p class="mt-3 font-semibold text-red-700">Permit expired</p>
                @endif
            @else
                <p class="mt-3 text-stone-600">No permit on file.</p>
            @endif
        </section>

        <section class="rounded-2xl border border-amber-200 bg-white p-6">
            <h2 class="text-xl font-bold">Dishes to try</h2>
            @if ($eatery->dishes->isNotEmpty())
                <ul class="mt-4 divide-y divide-amber-100">
                    @foreach ($eatery->dishes as $dish)
                        <li class="flex items-center justify-between gap-3 py-3">
                            <span class="font-medium">{{ $dish->name }}
                                @if ($dish->is_bestseller)
                                    <span class="ml-1 rounded-full bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-800">Bestseller</span>
                                @endif
                            </span>
                            <span class="font-semibold">₱{{ number_format((float) $dish->price, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="mt-3 text-stone-600">No dishes listed yet.</p>
            @endif
        </section>
    </div>

    <section class="mt-10">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-orange-700">Community favorites</p>
                <h2 class="mt-1 text-2xl font-bold">Reviews ({{ $eatery->reviews->count() }})</h2>
            </div>
            <x-rating :rating="$eatery->reviews->avg('rating')" :count="$eatery->reviews->count()" />
        </div>

        @if ($eatery->reviews->isNotEmpty())
            <div class="mt-5 space-y-4">
                @foreach ($eatery->reviews as $review)
                    <article class="rounded-2xl border border-amber-200 bg-white p-5">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <a href="{{ route('reviewers.show', $review->user) }}" class="font-semibold text-orange-800 hover:underline">{{ $review->user->name }}</a>
                                <p class="mt-1 text-xs text-stone-500">{{ $review->created_at->format('F j, Y') }}</p>
                            </div>
                            <x-rating :rating="$review->rating" />
                        </div>
                        <p class="mt-4 whitespace-pre-line text-stone-700">{{ $review->body }}</p>
                        <form method="POST" action="{{ route('reviews.destroy', $review) }}" class="mt-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm font-semibold text-red-700 hover:underline">Delete review</button>
                        </form>
                    </article>
                @endforeach
            </div>
        @else
            <p class="mt-5 rounded-2xl border border-dashed border-amber-300 bg-white p-6 text-stone-600">Be the first to leave a review.</p>
        @endif
    </section>

    <section class="mt-10 rounded-2xl border border-amber-200 bg-white p-6 sm:p-8">
        @if ($eatery->is_open)
            <h2 class="text-2xl font-bold">Share your experience</h2>
            <form method="POST" action="{{ route('eateries.reviews.store', $eatery) }}" class="mt-5 space-y-4">
                @csrf
                @if ($errors->any())
                    <div role="alert" class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
                        <p class="font-semibold">Please fix the following errors:</p>
                        <ul class="mt-2 list-inside list-disc text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div>
                    <label for="user_id" class="block text-sm font-semibold">Reviewer</label>
                    <select id="user_id" name="user_id" required class="mt-1 w-full rounded-lg border-stone-300 focus:border-orange-600 focus:ring-orange-600">
                        <option value="">Choose who is writing this review</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="rating" class="block text-sm font-semibold">Rating</label>
                    <select id="rating" name="rating" required class="mt-1 w-full rounded-lg border-stone-300 focus:border-orange-600 focus:ring-orange-600">
                        <option value="">Choose a rating</option>
                        @for ($rating = 5; $rating >= 1; $rating--)
                            <option value="{{ $rating }}" @selected(old('rating') == $rating)>{{ $rating }} {{ $rating === 1 ? 'star' : 'stars' }}</option>
                        @endfor
                    </select>
                    @error('rating') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="body" class="block text-sm font-semibold">Your review</label>
                    <textarea id="body" name="body" rows="4" required minlength="10" maxlength="1000" class="mt-1 w-full rounded-lg border-stone-300 focus:border-orange-600 focus:ring-orange-600">{{ old('body') }}</textarea>
                    @error('body') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="rounded-full bg-orange-700 px-6 py-3 font-semibold text-white hover:bg-orange-800">Post review</button>
            </form>
        @else
            <p class="font-semibold text-stone-700">This eatery is closed. Reviews are paused.</p>
        @endif
    </section>
@endsection
