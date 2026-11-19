@props(['rating' => null, 'count' => null])

@if ($count !== null && $count > 0)
    <span class="text-sm font-semibold text-amber-700">{{ number_format((float) $rating, 1) }} ★ ({{ $count }} {{ $count === 1 ? 'review' : 'reviews' }})</span>
@elseif ($rating !== null)
    <span class="text-sm font-semibold text-amber-700">{{ number_format((float) $rating, 1) }} ★</span>
@else
    <span class="text-sm text-stone-500">No reviews yet</span>
@endif
