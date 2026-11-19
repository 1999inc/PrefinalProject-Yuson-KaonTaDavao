@extends('layouts.app')

@section('title', "Kaon Ta! Davao – Yuson's Food Guide")

@section('content')
    <section class="rounded-3xl bg-orange-800 px-6 py-10 text-white sm:px-10 sm:py-14">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-orange-200">Davao City, one delicious stop at a time</p>
        <h1 class="mt-3 text-4xl font-black tracking-tight sm:text-5xl">Kaon Ta! Davao</h1>
        <p class="mt-4 max-w-2xl text-lg text-orange-50">Find neighborhood carinderias, discover local specialties, and share your favorite bites.</p>
        <a href="{{ route('eateries.index') }}" class="mt-7 inline-flex rounded-full bg-white px-5 py-3 font-semibold text-orange-800 hover:bg-orange-50">Explore eateries</a>
    </section>

    <section class="mt-8 grid gap-4 sm:grid-cols-3" aria-label="Guide statistics">
        @foreach ([['Eateries', $eateryCount], ['Districts', $districtCount], ['Reviews', $reviewCount]] as [$label, $count])
            <div class="rounded-2xl border border-amber-200 bg-white p-5">
                <p class="text-sm font-medium text-stone-500">{{ $label }}</p>
                <p class="mt-1 text-3xl font-bold text-stone-900">{{ $count }}</p>
            </div>
        @endforeach
    </section>

    <section class="mt-12">
        <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="text-sm font-semibold uppercase tracking-widest text-orange-700">Loved by locals</p>
                <h2 class="mt-1 text-2xl font-bold">Top Rated</h2>
            </div>
            <a href="{{ route('eateries.index') }}" class="font-semibold text-orange-800 hover:underline">View all eateries</a>
        </div>
        @if ($topEateries->isNotEmpty())
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($topEateries as $eatery)
                    <x-eatery-card :eatery="$eatery" />
                @endforeach
            </div>
        @else
            <p class="rounded-2xl border border-dashed border-amber-300 bg-white p-6 text-stone-600">No rated eateries yet.</p>
        @endif
    </section>
@endsection
