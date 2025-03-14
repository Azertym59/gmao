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
    Schema::create('dalles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('produit_id')->constrained()->onDelete('cascade');
        $table->float('largeur', 8, 2); // en mm
        $table->float('hauteur', 8, 2); // en mm
        $table->integer('nb_modules');
        $table->string('alimentation');
        $table->string('reference_dalle')->nullable(); // Numéro de série ou référence
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dalles');
    }
};
