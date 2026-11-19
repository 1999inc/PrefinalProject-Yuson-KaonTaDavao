@props(['eatery'])

@php
    $priceSymbols = ['budget' => '₱', 'mid' => '₱₱', 'splurge' => '₱₱₱'];
@endphp

<article class="flex h-full flex-col rounded-2xl border border-amber-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
    <div class="flex items-start justify-between gap-3">
        <h2 class="text-lg font-bold leading-snug text-stone-900">
            <a href="{{ route('eateries.show', $eatery) }}" class="hover:text-orange-700">{{ $eatery->name }}</a>
        </h2>
        @unless ($eatery->is_open)
            <span class="shrink-0 rounded-full bg-stone-100 px-2.5 py-1 text-xs font-semibold text-stone-600">Closed</span>
        @endunless
    </div>
    <p class="mt-2 text-sm text-stone-600">
        <a href="{{ route('districts.show', $eatery->district) }}" class="underline decoration-amber-300 underline-offset-2 hover:text-orange-700">{{ $eatery->district->name }}</a>
        <span class="mx-1">·</span>{{ $priceSymbols[$eatery->price_range] ?? $eatery->price_range }}
    </p>
    @if ($eatery->specialty)
        <p class="mt-3 text-sm text-stone-700">{{ $eatery->specialty }}</p>
    @endif
    <div class="mt-3 flex flex-wrap gap-2">
        @foreach ($eatery->features as $feature)
            <a href="{{ route('eateries.index', ['feature' => $feature->name]) }}" class="rounded-full bg-orange-50 px-2.5 py-1 text-xs font-medium text-orange-800 hover:bg-orange-100">{{ $feature->name }}</a>
        @endforeach
    </div>
    <div class="mt-auto pt-5">
        <x-rating :rating="$eatery->reviews_avg_rating" :count="$eatery->reviews_count" />
    </div>
</article>
