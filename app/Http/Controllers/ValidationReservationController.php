<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ValidationReservationController extends Controller
{
    /**
     * Liste toutes les demandes en attente pour le service logistique.
     */
    public function index()
    {
        $demandesEnAttente = Reservation::where('statut', 'en_attente')
            ->with(['user', 'salle']) // Pour récupérer les détails de l'utilisateur et de la salle
            ->orderBy('date', 'asc')
            ->get();

        return response()->json([
            'demandes' => $demandesEnAttente
        ], 200);
    }

    public function updateStatut(Request $request, $id)
    {
        // On valide proprement la requête
        $validated = $request->validate([
            'statut' => 'required|in:confirmee,refusee,annulee',
        ]);

        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json([
                'message' => 'Réservation introuvable.'
            ], 404);
        }

        // Mise à jour du statut avec les données validées
        $reservation->update([
            'statut' => $validated['statut']
        ]);

        $actionText = $validated['statut'] === 'confirmee' ? 'validée' : 'refusée';

        return response()->json([
            'message' => "La réservation a été {$actionText} avec succès.",
            'reservation' => $reservation
        ], 200);
    }

    /**
     * Valider ou refuser une réservation.
     */
    // public function updateStatut(Request $request, $id)
    // {
    //     $request->validate([
    //         'statut' => 'required|in:validee,refusee',
    //     ]);

    //     $reservation = Reservation::find($id);

    //     if (!$reservation) {
    //         return response()->json([
    //             'message' => 'Réservation introuvable.'
    //         ], 404);
    //     }

    //     // Mise à jour du statut
    //     $reservation->update([
    //         'statut' => $request->input('statut')
    //     ]);

    //     $actionText = $request->input('statut') === 'validee' ? 'validée' : 'refusée';

    //     return response()->json([
    //         'message' => "La réservation a été {$actionText} avec succès.",
    //         'reservation' => $reservation
    //     ], 200);
    // }
}
