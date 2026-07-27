<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Salle;
use App\Models\Reservation;

class ReservationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_qu_on_peut_creer_une_reservation_si_aucun_conflit(): void
    {
        // $response = $this->get('/');

        // $response->assertStatus(200);
        $user = User::factory()->create();
        $salle = Salle::factory()->create();

        // Reservation::create([
        //     'user_id' => $user->id,
        //     'salle_id' => $salle->id,
        //     'date' => '2026-10-01',
        //     'heure_debut' => '10:00:00',
        //     'heure_fin' => '12:00:00',
        //     'statut' => 'en_attente',
        //     'type' => 'demande',
        // ]);

        $payload = [
            'user_id' => $user->id,
            'salle_id' => $salle->id,
            'date' => '2026-08-10',
            'heure_debut' => '08:00',
            'heure_fin' => '10:00',
            'motif' => 'Cours magistral'
        ];

        // 2. On essaie d'en créer une deuxième qui se chevauche (ex: 11h - 13h)
        // $response = $this->postJson('/nouvelle-reservation', [
        //     'user_id' => $user->id,
        //     'salle_id' => $salle->id,
        //     'date' => '2026-10-01',
        //     'heure_debut' => '11:00:00',
        //     'heure_fin' => '13:00:00',
        // ]);
        $response = $this->postJson('/nouvelle-reservation', $payload);

        // 3. On vérifie que Laravel bloque bien avec un code 409
        // $response->assertStatus(409);
        // 4. On vérifie que la réponse est un succès (201 Created) et que la bdd contient l'entrée
        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Réservation effectuée avec succès !'
                 ]);
        $this->assertDatabaseHas('reservations', [
            'salle_id' => $salle->id,
            'date' => '2026-08-10',
            'heure_debut' => '08:00',
            'heure_fin' => '10:00',
        ]);
    }
    
    public function test_qu_on_bloque_une_reservation_en_cas_de_chevauchement_conflit()
    {
        // 1. On prépare les données de base
        $user = User::factory()->create();
        $salle = Salle::factory()->create();

        // 2. On insère une première réservation existante (de 08:00 à 10:00)
        Reservation::create([
            'user_id' => $user->id,
            'salle_id' => $salle->id,
            'date' => '2026-08-10',
            'heure_debut' => '08:00',
            'heure_fin' => '10:00',
            'type' => 'demande',
            'statut' => 'en_attente'
        ]);

        // 3. On tente de réserver un créneau qui chevauche (par exemple de 09:00 à 11:00)
        $payloadEnConflit = [
            'user_id' => $user->id,
            'salle_id' => $salle->id,
            'date' => '2026-08-10',
            'heure_debut' => '09:00',
            'heure_fin' => '11:00',
            'motif' => 'TP en chevauchement'
        ];

        $response = $this->postJson('/nouvelle-reservation', $payloadEnConflit);

        // 4. On s'attend à un code 409 (Conflit)
        $response->assertStatus(409)
                 ->assertJson([
                     'message' => 'Désolé, cette salle est déjà occupée sur ce créneau horaire.'
                 ]);
    }
}
