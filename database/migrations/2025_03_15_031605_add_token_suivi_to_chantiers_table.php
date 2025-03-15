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
        Schema::table('chantiers', function (Blueprint $table) {
            $table->string('token_suivi')->nullable()->after('reference');
            $table->timestamp('token_suivi_last_used_at')->nullable()->after('token_suivi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chantiers', function (Blueprint $table) {
            $table->dropColumn(['token_suivi', 'token_suivi_last_used_at']);
        });
    }
};
