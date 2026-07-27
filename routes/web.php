<?php

use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ValidationReservationController;
use Illuminate\Support\Facades\Route;

//C'est ici qu'on declare les adresses de notre application

Route::get('/', function () {
    return view('welcome');
});

//au lieu d'afficher une page elle enregistre les donnees 
Route::post('/nouvelle-reservation', [ReservationController::class, 'store']);

//route laravel qui dit a laravel quoi faire lorsqu'un utilisateur visite ce url
Route::get('/test-interface', function () {
    //si quelqu'un visite l'adresse /test-interface alors affiche la vue test
    return view('test');
});

//Route pour les services logistiques 
Route::get('/logistique/demandes', [ValidationReservationController::class, 'index']);
Route::patch('/logistique/nouvelle-reservation/{id}/statut', [ValidationReservationController::class, 'updateStatut']);