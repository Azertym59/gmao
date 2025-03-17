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
        Schema::table('led_datasheets', function (Blueprint $table) {
            $table->string('color')->default('black')->after('type')->comment('Couleur du PCB (black/white)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('led_datasheets', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};
