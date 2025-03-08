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
    Schema::create('clients', function (Blueprint $table) {
        $table->id();
        $table->string('nom');
        $table->string('prenom');
        $table->string('societe')->nullable();
        $table->string('adresse');
        $table->string('code_postal');
        $table->string('ville');
        $table->string('pays')->default('France');
        $table->string('email')->unique();
        $table->string('telephone');
        $table->text('notes')->nullable();
        $table->timestamps();
        $table->softDeletes(); // Pour pouvoir "supprimer" sans perdre les données
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
