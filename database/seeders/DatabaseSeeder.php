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
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // Crée 10 faux utilisateurs
        \App\Models\User::factory(10)->create();

        // Crée 5 fausses salles
        \App\Models\Salle::factory(5)->create();

        // Crée 20 fausses réservations (qui piocheront aléatoirement dans les utilisateurs et salles créés ci-dessus)
        \App\Models\Reservation::factory(20)->create();
    }
}
