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
        Schema::create('users', function (Blueprint $table) {
        //Schema classe de laravel qui permet de manipuler la base de donnees : creer modifier supprimer une table
        //create drop table
        //cree une nouvelle table nommee users
        //la fonction blueprint est une fonction anonyme a executer au moment de creer la table
        //blueprint recoit un objet table qui est la table en cours de creation
        //grace a lui on peut ajouter les colonnes a la table
        //blueprint est une classe qui decrit le plan de construction de la table
        $table->id(); // Crée une colonne 'id' auto-incrémentée (Clé primaire)
        $table->string('nom'); // Colonne VARCHAR pour le nom
        $table->string('prenom'); // Colonne VARCHAR pour le prénom
        $table->string('email')->unique(); // Email unique dans toute la BDD
        $table->string('password'); // Mot de passe (qui sera haché)
        
        // Notre gestion des 5 rôles avec une valeur par défaut
        $table->enum('role', ['enseignant', 'etudiant', 'association', 'logistique', 'admin'])
              ->default('etudiant');
              
        $table->boolean('actif')->default(true); // Compte actif ou désactivé
        $table->timestamps(); // Crée automatiquement 2 colonnes : created_at et updated_at
    });
        // Schema::create('users', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('email')->unique();
        //     $table->timestamp('email_verified_at')->nullable();
        //     $table->string('password');
        //     $table->rememberToken();
        //     $table->timestamps();
        // });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
