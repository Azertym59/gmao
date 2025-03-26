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
        Schema::table('produits', function (Blueprint $table) {
            $table->string('nom')->nullable()->after('id');
            $table->text('description')->nullable()->after('electronique_detail');
            $table->string('etat')->nullable()->after('description');
            $table->float('largeur_dalle', 8, 2)->nullable()->after('etat');
            $table->float('hauteur_dalle', 8, 2)->nullable()->after('largeur_dalle');
            $table->string('disposition_modules')->nullable()->after('hauteur_dalle')->comment('Format NxM, ex: 2x2, 3x3, 4x4');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn([
                'nom',
                'description',
                'etat',
                'largeur_dalle',
                'hauteur_dalle',
                'disposition_modules'
            ]);
        });
    }
};