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
    Schema::create('produits_catalogue', function (Blueprint $table) {
        $table->id();
        $table->string('marque');
        $table->string('modele');
        $table->float('pitch', 8, 2);
        $table->enum('utilisation', ['indoor', 'outdoor']);
        $table->enum('electronique', ['nova', 'linsn', 'dbstar', 'brompton', 'autre']);
        $table->string('electronique_detail')->nullable();
        $table->string('image_url')->nullable();
        $table->text('description')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits_catalogue');
    }
};
