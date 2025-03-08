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
    Schema::create('interventions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('module_id')->constrained()->onDelete('cascade');
        $table->foreignId('technicien_id')->constrained('users');
        $table->dateTime('date_debut');
        $table->dateTime('date_fin')->nullable();
        $table->dateTime('date_reprise')->nullable(); // Pour reprendre le chrono après pause
        $table->dateTime('date_pause')->nullable();
        $table->integer('temps_total')->default(0); // en secondes
        $table->boolean('is_completed')->default(false);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
