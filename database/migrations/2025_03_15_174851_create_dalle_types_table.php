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
        Schema::create('dalle_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained()->onDelete('cascade');
            $table->string('nom');
            $table->integer('largeur')->default(500);
            $table->integer('hauteur')->default(500);
            $table->string('disposition_modules')->default('2x2');
            $table->integer('nb_colonnes')->default(2);
            $table->integer('nb_lignes')->default(2);
            $table->string('disposition_type')->nullable();
            $table->text('disposition_schema')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dalle_types');
    }
};
