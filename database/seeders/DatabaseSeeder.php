<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Business;
use App\Models\Category;
use App\Models\Neighborhood;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'), // Or your preferred default password
                'email_verified_at' => now(),
            ]
        );

        $this->call(RolesAndPermissionsSeeder::class);

        $categories = Category::factory(5)->create();
        $neighborhoods = Neighborhood::factory(5)->create();

        $businesses = Business::factory(20)->create()->each(function ($business) use ($categories, $neighborhoods) {
            $business->categories()->attach($categories->random(rand(1, 2)));
            $business->neighborhoods()->attach($neighborhoods->random(rand(1, 2)));
        });

        Article::factory(20)->create(['author_id' => $admin->id])->each(function ($article) use ($categories, $neighborhoods, $businesses) {
            $article->categories()->attach($categories->random(rand(1, 2)));
            $article->neighborhoods()->attach($neighborhoods->random(rand(1, 2)));

            if (rand(0, 1)) {
                $article->mentionedBusinesses()->attach($businesses->random());
            }
        });
    }
}
