@extends('layouts.app')

@section('title', "Add an Eatery · Kaon Ta! Davao")

@section('content')
    <div class="mx-auto max-w-3xl">
        <p class="text-sm font-semibold uppercase tracking-widest text-orange-700">Grow the guide</p>
        <h1 class="mt-1 text-3xl font-bold">Add an Eatery</h1>
        <div class="mt-6 rounded-2xl border border-amber-200 bg-white p-6 sm:p-8">
            @include('eateries._form', ['eatery' => null])
        </div>
    </div>
@endsection
