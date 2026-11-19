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

test('guide pages render and eateries bind by slug', function () {
    $eatery = Eatery::where('slug', 'carinderia-ni-yuson')->firstOrFail();
    $district = District::where('name', 'Poblacion')->firstOrFail();
    $reviewer = User::where('email', 'tita.yuson@kaonta.test')->firstOrFail();

    $this->get(route('home'))->assertOk();
    $this->get(route('eateries.index'))->assertOk();
    $this->get(route('eateries.show', $eatery))->assertOk();
    $this->get(route('districts.index'))->assertOk();
    $this->get(route('districts.show', $district))->assertOk();
    $this->get(route('reviewers.show', $reviewer))->assertOk();
    $this->get('/eateries/not-a-real-slug')->assertNotFound();
});

test('closed eateries reject reviews without saving them', function () {
    $eatery = Eatery::where('is_open', false)->firstOrFail();
    $reviewCount = Review::count();

    $this->post(route('eateries.reviews.store', $eatery), [
        'user_id' => User::first()->id,
        'rating' => 5,
        'body' => 'This review should not be saved.',
    ])->assertForbidden();

    expect(Review::count())->toBe($reviewCount);
});

test('open eateries accept reviews and reviewers can delete them', function () {
    $eatery = Eatery::where('slug', 'carinderia-ni-yuson')->firstOrFail();
    $user = User::where('email', 'yuson@kaonta.test')->firstOrFail();

    $this->post(route('eateries.reviews.store', $eatery), [
        'user_id' => $user->id,
        'rating' => 4,
        'body' => 'A lovely meal in Davao.',
    ])->assertRedirect(route('eateries.show', $eatery))
        ->assertSessionHas('success', 'Salamat! Review posted.');

    $review = $eatery->reviews()->where('user_id', $user->id)->latest()->firstOrFail();

    $this->delete(route('reviews.destroy', $review))
        ->assertRedirect(route('eateries.show', $eatery))
        ->assertSessionHas('success', 'Review deleted.');

    expect(Review::whereKey($review->id)->exists())->toBeFalse();
});

test('eatery create update and delete use their relationships', function () {
    $district = District::where('name', 'Poblacion')->firstOrFail();
    $feature = Feature::where('name', 'Budget Meal')->firstOrFail();
    $data = [
        'name' => 'Test Carinderia',
        'district_id' => $district->id,
        'address' => '123 Test Street, Davao City',
        'price_range' => 'budget',
        'specialty' => 'Chicken soup',
        'features' => [$feature->id],
        'permit_no' => 'DVO-2026-98765',
        'expires_on' => '2027-12-31',
    ];

    $this->post(route('eateries.store'), $data)
        ->assertRedirect(route('eateries.show', 'test-carinderia'))
        ->assertSessionHas('success', 'Eatery added.');

    $eatery = Eatery::where('slug', 'test-carinderia')->firstOrFail();
    expect($eatery->is_open)->toBeFalse()
        ->and($eatery->permit->permit_no)->toBe('DVO-2026-98765')
        ->and($eatery->features->modelKeys())->toBe([$feature->id]);

    $this->put(route('eateries.update', $eatery), $data)
        ->assertRedirect(route('eateries.show', $eatery))
        ->assertSessionHas('success', 'Eatery updated.');

    $this->delete(route('eateries.destroy', $eatery))
        ->assertRedirect(route('eateries.index'))
        ->assertSessionHas('success', 'Eatery deleted.');

    expect(Eatery::whereKey($eatery->id)->exists())->toBeFalse();
});

test('eatery form requests use the required personalized messages', function () {
    $base = [
        'name' => 'Validation Test Eatery',
        'district_id' => District::firstOrFail()->id,
        'address' => '123 Test Street, Davao City',
        'price_range' => 'budget',
        'specialty' => 'Chicken soup',
        'features' => [Feature::firstOrFail()->id],
        'permit_no' => 'DVO-2026-98765',
        'expires_on' => '2027-12-31',
        'is_open' => '1',
    ];

    $cases = [
        ['name', "Kaon Ta Yuson already lists an eatery with this name.", ['name' => 'Carinderia ni Yuson']],
        ['features', 'Pick at least one feature.', ['features' => null]],
        ['permit_no', 'Permit number must look like DVO-2026-0523.', ['permit_no' => 'invalid']],
        ['expires_on', 'The permit must not be expired.', ['expires_on' => now()->subDay()->toDateString()]],
    ];

    foreach ($cases as [$field, $message, $overrides]) {
        $this->flushSession()
            ->from(route('eateries.create'))
            ->post(route('eateries.store'), array_replace($base, $overrides))
            ->assertSessionHasErrors([$field])
            ->assertSessionHas('errors', fn ($errors) => $errors->first($field) === $message);
    }

    $eatery = Eatery::where('slug', 'carinderia-ni-yuson')->firstOrFail();
    $reviewCases = [
        ['user_id', 'Choose who is writing this review.', ['user_id' => null]],
        ['rating', 'Ratings go from 1 to 5 stars only.', ['rating' => 6]],
        ['body', 'Kaon Ta Yuson: reviews need at least 10 characters.', ['body' => 'Too short']],
    ];

    foreach ($reviewCases as [$field, $message, $overrides]) {
        $this->flushSession()
            ->from(route('eateries.show', $eatery))
            ->post(route('eateries.reviews.store', $eatery), array_replace([
                'user_id' => User::firstOrFail()->id,
                'rating' => 5,
                'body' => 'A good meal worth reviewing.',
            ], $overrides))
            ->assertSessionHasErrors([$field])
            ->assertSessionHas('errors', fn ($errors) => $errors->first($field) === $message);
    }
});

