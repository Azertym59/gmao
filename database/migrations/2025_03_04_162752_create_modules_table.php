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
    Schema::create('modules', function (Blueprint $table) {
        $table->id();
        $table->foreignId('dalle_id')->constrained()->onDelete('cascade');
        $table->float('largeur', 8, 2);
        $table->float('hauteur', 8, 2);
        $table->integer('nb_pixels_largeur');
        $table->integer('nb_pixels_hauteur');
        $table->string('carte_reception')->nullable();
        $table->string('hub')->nullable();
        $table->string('driver')->nullable();
        $table->string('shift_register')->nullable();
        $table->string('buffer')->nullable();
        $table->enum('etat', ['non_commence', 'en_cours', 'defaillant', 'termine'])->default('non_commence');
        $table->foreignId('technicien_id')->nullable()->constrained('users');
        $table->boolean('est_occupe')->default(false);
        $table->string('reference_module')->nullable(); // Numéro de série ou référence
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
