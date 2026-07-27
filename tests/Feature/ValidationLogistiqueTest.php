<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Salle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ValidationLogistiqueTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_la_logique_peut_lister_les_demandes_en_attente(): void
    {
        // $response = $this->get('/');

        // $response->assertStatus(200);
        $userLogistique = User::factory()->create(['role' => 'logistique']);
        $user = User::factory()->create();
        $salle = Salle::factory()->create();

        // Création d'une réservation en attente
        Reservation::create([
            'user_id' => $user->id,
            'salle_id' => $salle->id,
            'date' => '2026-08-15',
            'heure_debut' => '10:00',
            'heure_fin' => '12:00',
            'type' => 'demande',
            'statut' => 'en_attente',
            'motif' => 'Besoin pour une association'
        ]);

        // $response = $this->getJson('/logistique/demandes');
        $response = $this->actingAs($userLogistique)->getJson('/logistique/demandes');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'demandes');
                //  ->assertJsonFragment([
                //      'statut' => 'en_attente',
                //      'motif' => 'Besoin pour une association'
                // ]);
    }

    public function test_la_logistique_peut_valider_ou_refuser_une_demande()
    {
        $userLogistique = User::factory()->create(['role' => 'logistique']);
        // $user = User::factory()->create();
        
        $userEtudiant = User::factory()->create();
        
        $salle = Salle::factory()->create();

        $reservation = Reservation::create([
            'user_id' => $userEtudiant->id,
            'salle_id' => $salle->id,
            'date' => '2026-08-15',
            'heure_debut' => '10:00',
            'heure_fin' => '12:00',
            'type' => 'demande',
            'statut' => 'en_attente'
        ]);

        //On simule la requete en s'authentifiant avec actingAs()
        $response = $this->actingAs($userLogistique)->patchJson("/logistique/nouvelle-reservation/{$reservation->id}/statut", [
            "statut" => "confirmee"
        ]);

        // On valide la demande
        // $response = $this->patchJson("/logistique/nouvelle-reservation/{$reservation->id}/statut", [
        //     'statut' => 'confirmee'
        // ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'La réservation a été validée avec succès.'
                 ]);

        // On vérifie en base de données que le statut a bien changé
        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'statut' => 'confirmee'
        ]);
    }

    public function test_un_etudiant_ne_peut_pas_valider_une_demande()
    {
        // On vérifie que la sécurité fonctionne bien pour bloquer les étudiants
        $userEtudiant = User::factory()->create(['role' => 'etudiant']);
        $salle = Salle::factory()->create();

        $reservation = Reservation::create([
            'user_id' => $userEtudiant->id,
            'salle_id' => $salle->id,
            'date' => '2026-08-15',
            'heure_debut' => '10:00',
            'heure_fin' => '12:00',
            'type' => 'demande',
            'statut' => 'en_attente'
        ]);

        $response = $this->actingAs($userEtudiant)->patchJson("/logistique/nouvelle-reservation/{$reservation->id}/statut", [
            'statut' => 'confirmee'
        ]);

        // On s'attend à être bloqué (403 Forbidden)
        $response->assertStatus(403);
    }
}
