<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('dalles', function (Blueprint $table) {
        $table->string('carte_reception')->nullable();
        $table->string('hub')->nullable();
    });
}

public function down()
{
    Schema::table('dalles', function (Blueprint $table) {
        $table->dropColumn('carte_reception');
        $table->dropColumn('hub');
    });
}
};
