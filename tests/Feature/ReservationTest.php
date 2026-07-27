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
    public function test_example(): void
    {
        // $response = $this->get('/');

        // $response->assertStatus(200);
        $user = User::factory()->create();
        $salle = Salle::factory()->create();

        Reservation::create([
            'user_id' => $user->id,
            'salle_id' => $salle->id,
            'date' => '2026-10-01',
            'heure_debut' => '10:00:00',
            'heure_fin' => '12:00:00',
            'statut' => 'en_attente',
            'type' => 'demande',
        ]);

        // 2. On essaie d'en créer une deuxième qui se chevauche (ex: 11h - 13h)
        $response = $this->postJson('/nouvelle-reservation', [
            'user_id' => $user->id,
            'salle_id' => $salle->id,
            'date' => '2026-10-01',
            'heure_debut' => '11:00:00',
            'heure_fin' => '13:00:00',
        ]);

        // 3. On vérifie que Laravel bloque bien avec un code 409
        $response->assertStatus(409);
    }
}
