<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécuter les migrations.
     */
    public function up(): void
    {
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('model');
            $table->string('ip_address')->nullable();
            $table->integer('port')->nullable()->default(9100);
            $table->float('label_width')->default(62); // en mm
            $table->float('label_height')->default(100); // en mm
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('connection_type')->default('network'); // network, usb, bluetooth
            $table->string('driver')->nullable();
            $table->json('additional_settings')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Inverser les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printers');
    }
};