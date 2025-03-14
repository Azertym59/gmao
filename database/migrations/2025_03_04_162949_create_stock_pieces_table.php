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
    Schema::create('stock_pieces', function (Blueprint $table) {
        $table->id();
        $table->enum('categorie', ['led', 'ic', 'masque', 'autre']);
        $table->string('marque')->nullable();
        $table->string('modele')->nullable();
        $table->string('reference');
        $table->text('description')->nullable();
        $table->integer('quantite')->default(0);
        $table->integer('seuil_alerte')->default(10);
        $table->decimal('prix_unitaire', 10, 2)->nullable();
        $table->string('fournisseur')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_pieces');
    }
};
