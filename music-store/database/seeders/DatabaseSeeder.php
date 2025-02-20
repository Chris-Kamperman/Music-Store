<?php

namespace Database\Seeders;

use App\Models\Album;
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
        Album::factory(10)->has(Song::factory()->count(5))->create();

        // Seed user_album table
        $users = User::all();
        $users->each(function ($user) {
            $user->albums()->attach(Album::all()->random()->id);
        });
    }
}
