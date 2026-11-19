<?php

namespace Database\Seeders;

use App\Models\Eatery;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $personalEatery = Eatery::where('slug', 'carinderia-ni-yuson')->firstOrFail();

        $personalEatery->reviews()->create([
            'user_id' => User::where('email', 'tita.yuson@kaonta.test')->value('id'),
            'rating' => 5,
            'body' => 'Lami kaayo! Balik-balikan ni Tita Yuson.',
        ]);
        foreach (User::where('id', '!=', User::where('email', 'tita.yuson@kaonta.test')->value('id'))
            ->inRandomOrder()->limit(2)->get() as $user) {
            Review::factory()->create([
                'eatery_id' => $personalEatery->id,
                'user_id' => $user->id,
            ]);
        }

        Eatery::where('id', '!=', $personalEatery->id)
            ->where('is_open', true)
            ->each(function (Eatery $eatery): void {
                $count = fake()->numberBetween(0, 6);

                for ($i = 0; $i < $count; $i++) {
                    $eatery->reviews()->create([
                        'user_id' => User::inRandomOrder()->value('id'),
                        'rating' => fake()->numberBetween(1, 5),
                        'body' => fake()->paragraph(),
                    ]);
                }
            });
    }
}
