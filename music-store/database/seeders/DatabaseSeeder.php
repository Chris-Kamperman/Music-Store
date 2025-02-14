<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Song;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(5)->create();
        Song::factory(10)->create();

        // Seed user_album table
        $users = User::all();
        $users->each(function ($user) {
            $user->albums()->attach(Song::inRandomOrder()->first());
        });
    }
}
