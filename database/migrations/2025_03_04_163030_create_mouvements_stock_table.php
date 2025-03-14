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
    Schema::create('mouvements_stock', function (Blueprint $table) {
        $table->id();
        $table->foreignId('stock_piece_id')->constrained()->onDelete('cascade');
        $table->foreignId('intervention_id')->nullable()->constrained();
        $table->integer('quantite');
        $table->enum('type', ['entree', 'sortie']);
        $table->string('commentaire')->nullable();
        $table->foreignId('user_id')->constrained(); // Utilisateur ayant effectué le mouvement
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvements_stock');
    }
};
