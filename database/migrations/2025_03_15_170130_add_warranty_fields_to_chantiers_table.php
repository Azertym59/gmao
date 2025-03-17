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
            $table->boolean('is_client_achat')->default(false)->comment('Indique si le client a acheté le produit chez nous');
            $table->boolean('is_under_warranty')->default(false)->comment('Indique si le produit est sous garantie');
            $table->date('warranty_end_date')->nullable()->comment('Date de fin de garantie');
            $table->string('warranty_type')->nullable()->comment('Type de garantie (standard, premium, etc.)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chantiers', function (Blueprint $table) {
            $table->dropColumn('is_client_achat');
            $table->dropColumn('is_under_warranty');
            $table->dropColumn('warranty_end_date');
            $table->dropColumn('warranty_type');
        });
    }
};
