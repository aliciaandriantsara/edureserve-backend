<?php

namespace Database\Factories;

use App\Models\Salle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Salle>
 */
class SalleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Génère des noms comme "Salle 102", "Salle 305", etc.
            'nom' => 'Salle ' . fake()->unique()->numberBetween(100, 599),
            
            // Choisit un bâtiment au hasard
            'batiment' => 'Bâtiment ' . fake()->randomElement(['A', 'B', 'C', 'D', 'E']),
            
            // Un étage réaliste, 0 pouvant être le rez-de-chaussée
            'etage' => fake()->numberBetween(0, 5),
            
            // Une capacité logique pour une salle de cours ou de réunion
            'capacite' => fake()->numberBetween(15, 150),
            
            // 90% de chance que la salle soit disponible (true), 10% en maintenance (false)
            'est_ouverte' => fake()->boolean(90),
            
            // "optional()" permet d'avoir parfois une description, et parfois "null", ce qui respecte ton "nullable()"
            'description' => fake()->optional()->sentence(),
    ];
    }
}
