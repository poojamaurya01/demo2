<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::truncate();
        User::factory(5)->create();

        User::factory(15)->create()->each(function ($user) {
            // Assign a random parent to 50% of users, leave the rest with null parent
            if (rand(0, 1) == 1) {
                $randomParent = User::inRandomOrder()->first();
                $user->update(['parent_id' => $randomParent->id]);
            }
        });

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
