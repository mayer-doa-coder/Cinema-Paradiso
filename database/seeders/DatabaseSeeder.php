<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create demo users with varied profiles
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'username' => 'johndoe',
            'password' => Hash::make('password'),
            'bio' => 'Movie enthusiast and critic. Love indie films and classic cinema.',
            'location' => 'New York, USA',
        ]);

        User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'username' => 'janesmith',
            'password' => Hash::make('password'),
            'bio' => 'Horror and thriller fan. Always looking for the next scare.',
            'location' => 'Los Angeles, USA',
        ]);

        User::factory()->create([
            'name' => 'Mike Wilson',
            'email' => 'mike@example.com',
            'username' => 'mikew',
            'password' => Hash::make('password'),
            'bio' => 'Sci-fi nerd and Marvel superfan. CGI is my love language.',
            'location' => 'London, UK',
        ]);

        User::factory()->create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah@example.com',
            'username' => 'sarahj',
            'password' => Hash::make('password'),
            'bio' => 'Romance and drama lover. Give me all the feels!',
            'location' => 'Toronto, Canada',
        ]);

        User::factory()->create([
            'name' => 'Alex Chen',
            'email' => 'alex@example.com',
            'username' => 'alexchen',
            'password' => Hash::make('password'),
            'bio' => 'Documentary and foreign film enthusiast. Cinema is art.',
            'location' => 'Singapore',
        ]);

        // Create additional random users
        User::factory(15)->create();
    }
}
