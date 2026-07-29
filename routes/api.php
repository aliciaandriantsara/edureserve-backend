<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Salle;

// 1. Route pour RÉCUPÉRER les salles
Route::get('/rooms', function () {
    return Salle::all()->map(function ($salle) {
        return [
            'id' => $salle->id,
            'name' => $salle->nom ?? 'Sans nom',
            'capacity' => $salle->capacite ?? 0,
            'equipment' => $salle->description ?? 'Aucun',
            'status' => $salle->est_ouverte ? 'Disponible' : 'Indisponible',
        ];
    });
});

// 2. Route pour CRÉER une nouvelle salle
Route::post('/rooms', function (Request $request) {
    // Validation des données reçues du formulaire React
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'capacity' => 'required|integer|min:1',
        'equipment' => 'nullable|string',
        'status' => 'required|string',
    ]);

    // Enregistrement en base de données avec le modèle Salle
    $salle = Salle::create([
        'nom' => $validated['name'],
        'batiment' => $request->input('building', 'Bâtiment Principal'),
        'etage' => $request->input('floor', 0),
        'capacite' => $validated['capacity'],
        'description' => $validated['equipment'] ?? '',
        'est_ouverte' => $validated['status'] === 'Disponible',
    ]);

    // On retourne la nouvelle salle formatée pour React
    return response()->json([
        'id' => $salle->id,
        'name' => $salle->nom,
        'capacity' => $salle->capacite,
        'equipment' => $salle->description ?? 'Aucun',
        'status' => $salle->est_ouverte ? 'Disponible' : 'Indisponible',
    ], 201);
});