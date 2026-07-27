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
        Schema::create('materiels', function (Blueprint $table) {
        $table->id();
        
        // Clé étrangère liée à la table 'salles'
        // onDelete('cascade') signifie : si une salle est supprimée, ses matériels le sont aussi
        $table->foreignId('salle_id')->constrained('salles')->onDelete('cascade');
        
        $table->string('nom'); // Ex: "Vidéoprojecteur", "Tableau blanc"
        $table->integer('quantite')->default(1);
        $table->timestamps();
    });
    //     Schema::create('materiels', function (Blueprint $table) {
    //         $table->id();
    //         $table->timestamps();
    //     });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiels');
    }
};
