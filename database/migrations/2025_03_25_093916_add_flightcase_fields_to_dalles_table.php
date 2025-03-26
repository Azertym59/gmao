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
            $table->string('flightcase_number')->nullable()->after('reference_dalle');
            $table->integer('dalle_number')->nullable()->after('flightcase_number');
            $table->boolean('is_virtual')->default(false)->after('dalle_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dalles', function (Blueprint $table) {
            $table->dropColumn('flightcase_number');
            $table->dropColumn('dalle_number');
            $table->dropColumn('is_virtual');
        });
    }
};
