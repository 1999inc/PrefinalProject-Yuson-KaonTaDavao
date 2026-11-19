<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Paul Yuson',
            'email' => 'yuson@kaonta.test',
            'password' => 'kaonta2026',
        ]);

        User::create([
            'name' => 'Tita Yuson',
            'email' => 'tita.yuson@kaonta.test',
            'password' => 'kaonta2026',
        ]);

        User::factory(10)->create();
    }
}
