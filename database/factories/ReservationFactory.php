<?php

namespace Database\Factories;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // On génère une heure de début logique (ex: entre 8h et 16h)
        $heureDebut = fake()->numberBetween(8, 16);
        
        // On définit une durée de cours/réunion (entre 1h et 3h)
        $duree = fake()->numberBetween(1, 3);
        
        // On calcule l'heure de fin
        $heureFin = $heureDebut + $duree;

        return [
            // On pioche un utilisateur et une salle au hasard parmi ceux déjà créés
            'user_id' => \App\Models\User::inRandomOrder()->first()->id ?? \App\Models\User::factory(),
            'salle_id' => \App\Models\Salle::inRandomOrder()->first()->id ?? \App\Models\Salle::factory(),
            
            // On génère une date aléatoire entre aujourd'hui et dans 2 mois au format YYYY-MM-DD
            'date' => fake()->dateTimeBetween('now', '+2 months')->format('Y-m-d'),
            
            // On formate les heures pour qu'elles ressemblent à "08:00:00" ou "14:00:00"
            'heure_debut' => sprintf('%02d:00:00', $heureDebut),
            'heure_fin' => sprintf('%02d:00:00', $heureFin),
            
            // Les statuts et types définis dans tes enums
            'statut' => fake()->randomElement(['en_attente', 'confirmee', 'refusee', 'annulee']),
            'type' => fake()->randomElement(['directe', 'demande']),
            
            // Un motif textuel aléatoire (parfois nul grâce à optional())
            'motif' => fake()->optional()->sentence(),
            
            // On laisse null par défaut pour simplifier le jeu d'essai
            'motif_refus' => null,
        ];
    }
}
