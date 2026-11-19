<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\District;
use App\Models\Eatery;
use App\Models\Feature;
use App\Models\Permit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EaterySeeder extends Seeder
{
    public function run(): void
    {
        $eatery = Eatery::create([
            'district_id' => District::where('name', 'Poblacion')->value('id'),
            'name' => 'Carinderia ni Yuson',
            'slug' => Str::slug('Carinderia ni Yuson'),
            'address' => 'San Pedro St., Poblacion, Davao City',
            'price_range' => 'budget',
            'specialty' => 'Tinolang Manok',
            'is_open' => true,
        ]);

        $eatery->permit()->create([
            'permit_no' => 'DVO-2026-0523',
            'expires_on' => '2027-05-23',
        ]);

        $eatery->features()->sync(
            Feature::whereIn('name', ['Suki ni Yuson', 'Budget Meal'])->pluck('id')
        );

        $eatery->dishes()->createMany([
            ['name' => 'Tinolang Manok', 'price' => 85.00, 'is_bestseller' => true],
            ['name' => 'Kinilaw na Tuna', 'price' => 120.00],
            ['name' => 'Durian Shake', 'price' => 75.00],
        ]);

        for ($i = 0; $i < 14; $i++) {
            $factory = Eatery::factory();
            if ($i < 3) {
                $factory = $factory->closed();
            }

            $factoryEatery = $factory->create();
            $factoryEatery->permit()->save(Permit::factory()->make([
                'eatery_id' => $factoryEatery->id,
                'expires_on' => $i === 0 ? now()->subDay()->toDateString() : now()->addYear()->toDateString(),
            ]));

            $factoryEatery->dishes()->saveMany(
                Dish::factory()->count(fake()->numberBetween(2, 5))->make([
                    'eatery_id' => $factoryEatery->id,
                ])
            );

            $factoryEatery->features()->attach(
                Feature::inRandomOrder()->limit(fake()->numberBetween(1, 3))->pluck('id')
            );
        }
    }
}
