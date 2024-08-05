<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\Article;
use App\Models\Campaign;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            IndoRegionProvinceSeeder::class,
            IndoRegionRegencySeeder::class,
            IndoRegionDistrictSeeder::class,
            IndoRegionVillageSeeder::class,
        ]);

        // Admin
        User::factory()->setIdToOne()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
        ]);

        Article::factory()->create([
            'user_id' => 1,
            'title' => 'Default',
            'content' => 'Default',
            'approved_at' => now(),
        ]);

        Event::factory()->create([
            'user_id' => 1,
            'name' => 'Default',
            'description' => 'Default',
            'start_time' => now(),
            'end_time' => now()->addHours(2),
            'location' => 'Default',
            'url' => 'https://google.com',
        ]);

        Campaign::factory()->create([
            'user_id' => 1,
            'title' => 'Default',
            'name' => 'Default',
            'content' => 'Default',
        ]);

        //Testing
        // User::factory()->setIdToOne()->create([
        //     'name' => 'Admin',
        //     'email' => 'admin@gmail.com',
        //     'password' => 'admin123',
        // ])->each(function ($user) {
        //     $user->articles()->saveMany(Article::factory(100)->make());
        //     $user->events()->saveMany(Event::factory(100)->make());
        //     $user->campaigns()->saveMany(Campaign::factory(100)->make());
        // });
    }
}
