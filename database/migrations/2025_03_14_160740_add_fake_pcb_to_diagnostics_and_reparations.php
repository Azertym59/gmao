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
        Schema::table('diagnostics', function (Blueprint $table) {
            $table->boolean('pose_fake_pcb')->default(false)->after('composant_defectueux');
        });
        
        Schema::table('reparations', function (Blueprint $table) {
            $table->boolean('fake_pcb_pose')->default(false)->after('resultat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diagnostics', function (Blueprint $table) {
            $table->dropColumn('pose_fake_pcb');
        });
        
        Schema::table('reparations', function (Blueprint $table) {
            $table->dropColumn('fake_pcb_pose');
        });
    }
};
