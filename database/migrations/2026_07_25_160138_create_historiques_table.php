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
        Schema::create('historiques', function (Blueprint $table) {
        $table->id();
        $table->foreignId('reservation_id')->nullable()->constrained('reservations')->onDelete('set null');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        
        $table->enum('action', ['creation', 'validation', 'refus', 'annulation']);
        $table->timestamp('date_action'); // Date et heure précises de l'action
        $table->text('detail')->nullable();
        $table->timestamps();
        });
        // Schema::create('historiques', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historiques');
    }
};
