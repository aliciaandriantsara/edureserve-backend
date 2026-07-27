<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
        $table->id();
        
        // Liens vers l'utilisateur qui réserve et la salle réservée
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('salle_id')->constrained('salles')->onDelete('cascade');
        
        $table->date('date'); // Ex: 2026-10-24
        $table->time('heure_debut'); // Ex: 08:00:00
        $table->time('heure_fin'); // Ex: 10:00:00
        
        // État de la demande
        $table->enum('statut', ['en_attente', 'confirmee', 'refusee', 'annulee'])->default('en_attente');
        $table->enum('type', ['directe', 'demande']); // "directe" pour Enseignant, "demande" pour Étudiant
        
        $table->text('motif')->nullable(); // Ex: "Cours d'Algorithmique"
        $table->text('motif_refus')->nullable(); // Rempli si la logistique refuse
        $table->timestamps();

        // La contrainte d'unicité SQL dont nous avons parlé !
        $table->unique(['salle_id', 'date', 'heure_debut', 'heure_fin']);
    });
        // Schema::create('reservations', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
