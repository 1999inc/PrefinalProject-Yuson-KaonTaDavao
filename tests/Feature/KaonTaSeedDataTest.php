<?php

use App\Models\District;
use App\Models\Eatery;
use App\Models\Feature;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('seeded project data matches the personalized handout totals', function () {
    expect(District::count())->toBe(6)
        ->and(Feature::count())->toBe(6)
        ->and(User::count())->toBe(12)
        ->and(Eatery::count())->toBe(15)
        ->and(Eatery::where('is_open', false)->count())->toBe(3)
        ->and(\App\Models\Permit::count())->toBe(15);

    $eatery = Eatery::where('slug', 'carinderia-ni-yuson')->firstOrFail();
    expect($eatery->permit->permit_no)->toBe('DVO-2026-0523')
        ->and($eatery->permit->expires_on->toDateString())->toBe('2027-05-23')
        ->and($eatery->features->pluck('name')->sort()->values()->all())->toBe(['Budget Meal', 'Suki ni Yuson'])
        ->and($eatery->dishes()->count())->toBe(3)
        ->and($eatery->reviews()->count())->toBeGreaterThanOrEqual(3)
        ->and(Review::whereHas('eatery', fn ($query) => $query->where('is_open', false))->count())->toBe(0);

    $auntReview = $eatery->reviews()
        ->where('user_id', User::where('email', 'tita.yuson@kaonta.test')->value('id'))
        ->firstOrFail();
    expect($auntReview->rating)->toBe(5)
        ->and($auntReview->body)->toBe('Lami kaayo! Balik-balikan ni Tita Yuson.');
});
