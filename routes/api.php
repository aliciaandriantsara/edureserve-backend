<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Salle; // Assure-toi d'importer ton modèle Salle

// Route API pour récupérer la liste des salles, avec le mapping correct
Route::get('/rooms', function () {
    return Salle::all()->map(function ($salle) {
        // Nous "mappons" les champs français vers les clés React en anglais
        return [
            'id' => $salle->id,
            'name' => $salle->nom ?? 'Sans nom', // Clé 'name' => DB 'nom'
            'capacity' => $salle->capacite ?? 0,   // Clé 'capacity' => DB 'capacite'
            'equipment' => $salle->description ?? 'Aucun équipement', // Clé 'equipment' => DB 'description'
            'status' => $salle->est_ouverte ? 'Disponible' : 'Indisponible', // Clé 'status' => DB 'est_ouverte'
            // Tu peux aussi ajouter des données supplémentaires si tu le souhaites :
            'building' => $salle->batiment,
            'floor' => $salle->etage,
        ];
    });
});