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
        // Ajouter les colonnes position_lettre, position_x et position_y à la table modules
        Schema::table('modules', function (Blueprint $table) {
            if (!Schema::hasColumn('modules', 'position_lettre')) {
                $table->string('position_lettre')->nullable()->after('reference_module');
            }
            if (!Schema::hasColumn('modules', 'position_x')) {
                $table->integer('position_x')->nullable()->after('position_lettre');
            }
            if (!Schema::hasColumn('modules', 'position_y')) {
                $table->integer('position_y')->nullable()->after('position_x');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropColumn(['position_lettre', 'position_x', 'position_y']);
        });
    }
};