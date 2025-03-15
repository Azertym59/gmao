<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Utiliser une approche qui fonctionne aussi bien avec SQLite qu'avec MySQL
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                // SQLite ne supporte pas ENUM, donc utiliser string
                $table->string('role')->default('technicien')->after('email');
            });
        }
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};