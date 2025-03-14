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
        if (!Schema::hasTable('printers')) {
            Schema::create('printers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type')->default('standard');
                $table->boolean('is_default')->default(false);
                $table->json('options')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('printers', function (Blueprint $table) {
                // Add type field if it doesn't exist
                if (!Schema::hasColumn('printers', 'type')) {
                    $table->string('type')->default('standard')->after('name');
                }
                
                // Add is_default field if it doesn't exist
                if (!Schema::hasColumn('printers', 'is_default')) {
                    $table->boolean('is_default')->default(false)->after('type');
                }
                
                // Add options field if it doesn't exist
                if (!Schema::hasColumn('printers', 'options')) {
                    $table->json('options')->nullable()->after('is_default');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('printers', function (Blueprint $table) {
            // You can remove these columns if needed, but it's often safer to keep them
        });
    }
};