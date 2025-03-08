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
    Schema::create('reparations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('intervention_id')->constrained()->onDelete('cascade');
        $table->integer('nb_leds_remplacees')->default(0);
        $table->integer('nb_ic_remplaces')->default(0);
        $table->integer('nb_masques_remplaces')->default(0);
        $table->text('remarques')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reparations');
    }
};
