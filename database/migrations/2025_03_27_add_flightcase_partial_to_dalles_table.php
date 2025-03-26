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
        Schema::table('dalles', function (Blueprint $table) {
            $table->boolean('flightcase_partial')->default(false)->after('dalle_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dalles', function (Blueprint $table) {
            $table->dropColumn('flightcase_partial');
        });
    }
};
