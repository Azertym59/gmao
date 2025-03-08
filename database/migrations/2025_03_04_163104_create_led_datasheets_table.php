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
    Schema::create('led_datasheets', function (Blueprint $table) {
        $table->id();
        $table->string('type'); // SMD, DIP, MiniLED
        $table->string('reference');
        $table->integer('nb_poles');
        $table->string('disposition'); // ex: '3+3', '2+2'
        $table->enum('position_chanfrein', ['haut_gauche', 'haut_droit', 'bas_gauche', 'bas_droit']);
        $table->json('configuration_poles'); // Stocke les détails de chaque pôle
        $table->text('notes')->nullable();
        $table->foreignId('user_id')->constrained(); // Créateur du datasheet
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('led_datasheets');
    }
};
