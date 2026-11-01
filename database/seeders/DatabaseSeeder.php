<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::where('email', 'magazijn@jamin.nl')->delete();

        $activeUser = User::find(4);
        if ($activeUser) {
            $activeUser->update([
                'name' => 'Magazijnmedewerker',
                'email' => 'magazijnmedewerker@jamin.nl',
                'role' => 'Magazijnmedewerker',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]);
        } else {
            User::updateOrCreate(
                ['email' => 'magazijnmedewerker@jamin.nl'],
                [
                    'name' => 'Magazijnmedewerker',
                    'role' => 'Magazijnmedewerker',
                    'password' => bcrypt('password123'),
                    'email_verified_at' => now(),
                ]
            );
        }

        User::updateOrCreate(
            ['email' => 'klant@jamin.nl'],
            [
                'name' => 'Klant',
                'role' => 'Klant',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@jamin.nl'],
            [
                'name' => 'Admin',
                'role' => 'Admin',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );

        $this->call(JaminSeeder::class);
    }
}
