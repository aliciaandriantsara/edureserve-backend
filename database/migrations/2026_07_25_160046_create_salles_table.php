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
        Schema::create('salles', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // Ex: "Salle 100" ou "Agora"
            $table->string('batiment'); // Ex: "Bâtiment A"
            $table->integer('etage'); // Ex: 1, 2, 4
            $table->integer('capacite'); // Ex: 40 places
            $table->boolean('est_ouverte')->default(true); // true = disponible, false = en maintenance
            $table->text('description')->nullable(); // description facultative (nullable = peut être vide)
            $table->timestamps();
        });    
        // Schema::create('salles', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salles');
    }
};
