<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Models\Reservation; // On appelle notre modèle !
use App\Http\Requests\StoreReservationRequest;

class ReservationController extends Controller
{
    // public function store(Request $request)
    // {
    //     // 1. On récupère les données envoyées par l'utilisateur (depuis l'interface)
    //     $salle_id = $request->input('salle_id');
    //     $date = $request->input('date');
    //     $heure_debut = $request->input('heure_debut');
    //     $heure_fin = $request->input('heure_fin');

    //     // 2. LA LOGIQUE DE CHEVAUCHEMENT (Overlap)
    //     // On cherche s'il existe DÉJÀ une réservation pour cette salle, à cette date, 
    //     // dont les horaires se croisent avec notre demande.
    //     $conflit = Reservation::where('salle_id', $salle_id)
    //         ->where('date', $date)
    //         ->where(function ($query) use ($heure_debut, $heure_fin) {
    //             $query->where(function ($q) use ($heure_debut, $heure_fin) {
    //                 // Cas 1 : La nouvelle réservation commence pendant une réservation existante
    //                 $q->where('heure_debut', '<=', $heure_debut)
    //                   ->where('heure_fin', '>', $heure_debut);
    //             })->orWhere(function ($q) use ($heure_debut, $heure_fin) {
    //                 // Cas 2 : La nouvelle réservation se termine pendant une réservation existante
    //                 $q->where('heure_debut', '<', $heure_fin)
    //                   ->where('heure_fin', '>=', $heure_fin);
    //             })->orWhere(function ($q) use ($heure_debut, $heure_fin) {
    //                 // Cas 3 : La nouvelle réservation englobe totalement une réservation existante
    //                 $q->where('heure_debut', '>=', $heure_debut)
    //                   ->where('heure_fin', '<=', $heure_fin);
    //             });
    //         })
    //         ->exists(); // Renvoie 'true' si au moins un conflit est trouvé

    //     // 3. LA DÉCISION DU CONTRÔLEUR
    //     if ($conflit) {
    //         // Si conflit = vrai, on bloque tout et on renvoie une erreur à l'interface
    //         return response()->json([
    //             'message' => 'Désolé, cette salle est déjà occupée sur ce créneau horaire.'
    //         ], 409); // 409 est le code HTTP pour "Conflit"
    //     }

    //     // Si aucun conflit, on sauvegarde dans la base de données !
    //     $nouvelleReservation = Reservation::create([
    //         'user_id' => $request->input('user_id'),
    //         'salle_id' => $salle_id,
    //         'date' => $date,
    //         'heure_debut' => $heure_debut,
    //         'heure_fin' => $heure_fin,
    //         'type' => 'demande',
    //         'statut' => 'en_attente'
    //     ]);

    //     // On répond que tout s'est bien passé
    //     return response()->json([
    //         'message' => 'Réservation effectuée avec succès !',
    //         'reservation' => $nouvelleReservation
    //     ], 201); // 201 est le code HTTP pour "Créé"

    // //     $conflit = Reservation::where('salle_id', $salle_id)
    // // ->where('date', $date)
    // // // On ignore les réservations qui auraient été annulées ou refusées
    // // ->whereNotIn('statut', ['annulee', 'refusee']) 
    // // ->where(function ($query) use ($heure_debut, $heure_fin) {
    // //     // Formule universelle de chevauchement d'intervalles
    // //     $query->where('heure_debut', '<', $heure_fin)
    // //           ->where('heure_fin', '>', $heure_debut);
    // // })
    // // ->exists();
    // }
    public function store(StoreReservationRequest $request)
    {
        // Les données sont déjà validées ici grâce à StoreReservationRequest
        $salle_id = $request->input('salle_id');
        $date = $request->input('date');
        $heure_debut = $request->input('heure_debut');
        $heure_fin = $request->input('heure_fin');

        // Vérification des conflits en ignorant les réservations annulées ou refusées
        $conflit = Reservation::where('salle_id', $salle_id)
            ->where('date', $date)
            ->whereNotIn('statut', ['annulee', 'refusee'])
            ->where(function ($query) use ($heure_debut, $heure_fin) {
                $query->where(function ($q) use ($heure_debut, $heure_fin) {
                    $q->where('heure_debut', '<=', $heure_debut)
                      ->where('heure_fin', '>', $heure_debut);
                })->orWhere(function ($q) use ($heure_debut, $heure_fin) {
                    $q->where('heure_debut', '<', $heure_fin)
                      ->where('heure_fin', '>=', $heure_fin);
                })->orWhere(function ($q) use ($heure_debut, $heure_fin) {
                    $q->where('heure_debut', '>=', $heure_debut)
                      ->where('heure_fin', '<=', $heure_fin);
                });
            })
            ->exists();

        if ($conflit) {
            return response()->json([
                'message' => 'Désolé, cette salle est déjà occupée sur ce créneau horaire.'
            ], 409);
        }

        // Enregistrement de la réservation
        $nouvelleReservation = Reservation::create([
            'user_id' => $request->input('user_id'),
            'salle_id' => $salle_id,
            'date' => $date,
            'heure_debut' => $heure_debut,
            'heure_fin' => $heure_fin,
            'type' => $request->input('type', 'demande'),
            'statut' => 'en_attente',
            'motif' => $request->input('motif'),
        ]);

        return response()->json([
            'message' => 'Réservation effectuée avec succès !',
            'reservation' => $nouvelleReservation
        ], 201);
    }
}