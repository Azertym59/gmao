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
    Schema::create('produits', function (Blueprint $table) {
        $table->id();
        $table->foreignId('chantier_id')->constrained()->onDelete('cascade');
        $table->string('marque');
        $table->string('modele');
        $table->float('pitch', 8, 2); // ex: 2.5 mm
        $table->enum('utilisation', ['indoor', 'outdoor']);
        $table->enum('electronique', ['nova', 'linsn', 'dbstar', 'brompton', 'autre']);
        $table->string('electronique_detail')->nullable(); // Si 'autre' est sélectionné
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
