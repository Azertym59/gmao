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
            $table->string('bain_couleur')->nullable()->after('electronique_detail');
            $table->string('hub')->nullable()->after('bain_couleur');
            $table->string('alimentation')->nullable()->after('hub');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn(['bain_couleur', 'hub', 'alimentation']);
        });
    }
};
