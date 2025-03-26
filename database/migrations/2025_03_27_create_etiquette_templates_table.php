<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('etiquette_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['chantier', 'dalle', 'module']);
            $table->float('width')->default(62);  // Label width in mm
            $table->float('height')->default(100); // Label height in mm
            $table->enum('size_type', ['fixed', 'continuous'])->default('continuous');
            $table->boolean('is_default')->default(false);
            $table->text('template_data')->nullable(); // JSON data for template design
            $table->timestamps();
        });

        // Create default templates
        $this->createDefaultTemplates();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etiquette_templates');
    }

    /**
     * Create default templates for each type
     */
    private function createDefaultTemplates(): void
    {
        $types = ['chantier', 'dalle', 'module'];
        
        foreach ($types as $type) {
            DB::table('etiquette_templates')->insert([
                'name' => 'Template ' . ucfirst($type) . ' par défaut',
                'type' => $type,
                'width' => 62,
                'height' => 100,
                'size_type' => 'continuous',
                'is_default' => true,
                'template_data' => $this->getDefaultTemplateData($type),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Get default template data for each type
     */
    private function getDefaultTemplateData($type): string
    {
        $baseElements = [
            [
                'id' => 'title_' . uniqid(),
                'type' => 'text',
                'x' => 10,
                'y' => 10,
                'width' => 228,
                'height' => 30,
                'content' => ucfirst($type) . ' TecaLED',
                'fontSize' => 16,
                'fontWeight' => 'bold',
                'textAlign' => 'center',
                'color' => '#000000',
            ],
            [
                'id' => 'qrcode_' . uniqid(),
                'type' => 'qrcode',
                'x' => 84,
                'y' => 170,
                'width' => 80,
                'height' => 80,
                'content' => '{{qrcode_url}}',
            ],
            [
                'id' => 'date_' . uniqid(),
                'type' => 'variable',
                'x' => 10,
                'y' => 260,
                'width' => 228,
                'height' => 20,
                'content' => '{{date}}',
                'fontSize' => 12,
                'fontWeight' => 'normal',
                'textAlign' => 'center',
                'color' => '#555555',
            ],
        ];
        
        switch ($type) {
            case 'chantier':
                return json_encode(array_merge($baseElements, [
                    [
                        'id' => 'client_label_' . uniqid(),
                        'type' => 'text',
                        'x' => 10,
                        'y' => 50,
                        'width' => 80,
                        'height' => 20,
                        'content' => 'Client:',
                        'fontSize' => 12,
                        'fontWeight' => 'bold',
                        'textAlign' => 'left',
                        'color' => '#000000',
                    ],
                    [
                        'id' => 'client_value_' . uniqid(),
                        'type' => 'variable',
                        'x' => 100,
                        'y' => 50,
                        'width' => 138,
                        'height' => 20,
                        'content' => '{{client}}',
                        'fontSize' => 12,
                        'fontWeight' => 'normal',
                        'textAlign' => 'left',
                        'color' => '#000000',
                    ],
                    [
                        'id' => 'ref_label_' . uniqid(),
                        'type' => 'text',
                        'x' => 10,
                        'y' => 80,
                        'width' => 80,
                        'height' => 20,
                        'content' => 'Référence:',
                        'fontSize' => 12,
                        'fontWeight' => 'bold',
                        'textAlign' => 'left',
                        'color' => '#000000',
                    ],
                    [
                        'id' => 'ref_value_' . uniqid(),
                        'type' => 'variable',
                        'x' => 100,
                        'y' => 80,
                        'width' => 138,
                        'height' => 20,
                        'content' => '{{reference}}',
                        'fontSize' => 12,
                        'fontWeight' => 'normal',
                        'textAlign' => 'left',
                        'color' => '#000000',
                    ],
                ]));
                
            case 'dalle':
                return json_encode(array_merge($baseElements, [
                    [
                        'id' => 'chantier_label_' . uniqid(),
                        'type' => 'text',
                        'x' => 10,
                        'y' => 50,
                        'width' => 80,
                        'height' => 20,
                        'content' => 'Chantier:',
                        'fontSize' => 12,
                        'fontWeight' => 'bold',
                        'textAlign' => 'left',
                        'color' => '#000000',
                    ],
                    [
                        'id' => 'chantier_value_' . uniqid(),
                        'type' => 'variable',
                        'x' => 100,
                        'y' => 50,
                        'width' => 138,
                        'height' => 20,
                        'content' => '{{chantier}}',
                        'fontSize' => 12,
                        'fontWeight' => 'normal',
                        'textAlign' => 'left',
                        'color' => '#000000',
                    ],
                    [
                        'id' => 'numero_label_' . uniqid(),
                        'type' => 'text',
                        'x' => 10,
                        'y' => 80,
                        'width' => 80,
                        'height' => 20,
                        'content' => 'Numéro:',
                        'fontSize' => 12,
                        'fontWeight' => 'bold',
                        'textAlign' => 'left',
                        'color' => '#000000',
                    ],
                    [
                        'id' => 'numero_value_' . uniqid(),
                        'type' => 'variable',
                        'x' => 100,
                        'y' => 80,
                        'width' => 138,
                        'height' => 20,
                        'content' => '{{numero}}',
                        'fontSize' => 12,
                        'fontWeight' => 'normal',
                        'textAlign' => 'left',
                        'color' => '#000000',
                    ],
                    [
                        'id' => 'flightcase_label_' . uniqid(),
                        'type' => 'text',
                        'x' => 10,
                        'y' => 110,
                        'width' => 80,
                        'height' => 20,
                        'content' => 'Flightcase:',
                        'fontSize' => 12,
                        'fontWeight' => 'bold',
                        'textAlign' => 'left',
                        'color' => '#000000',
                    ],
                    [
                        'id' => 'flightcase_value_' . uniqid(),
                        'type' => 'variable',
                        'x' => 100,
                        'y' => 110,
                        'width' => 138,
                        'height' => 20,
                        'content' => '{{flightcase}}',
                        'fontSize' => 12,
                        'fontWeight' => 'normal',
                        'textAlign' => 'left',
                        'color' => '#000000',
                    ],
                ]));
                
            case 'module':
                return json_encode(array_merge($baseElements, [
                    [
                        'id' => 'dalle_label_' . uniqid(),
                        'type' => 'text',
                        'x' => 10,
                        'y' => 50,
                        'width' => 80,
                        'height' => 20,
                        'content' => 'Dalle:',
                        'fontSize' => 12,
                        'fontWeight' => 'bold',
                        'textAlign' => 'left',
                        'color' => '#000000',
                    ],
                    [
                        'id' => 'dalle_value_' . uniqid(),
                        'type' => 'variable',
                        'x' => 100,
                        'y' => 50,
                        'width' => 138,
                        'height' => 20,
                        'content' => '{{dalle}}',
                        'fontSize' => 12,
                        'fontWeight' => 'normal',
                        'textAlign' => 'left',
                        'color' => '#000000',
                    ],
                    [
                        'id' => 'position_label_' . uniqid(),
                        'type' => 'text',
                        'x' => 10,
                        'y' => 80,
                        'width' => 80,
                        'height' => 20,
                        'content' => 'Position:',
                        'fontSize' => 12,
                        'fontWeight' => 'bold',
                        'textAlign' => 'left',
                        'color' => '#000000',
                    ],
                    [
                        'id' => 'position_value_' . uniqid(),
                        'type' => 'variable',
                        'x' => 100,
                        'y' => 80,
                        'width' => 138,
                        'height' => 20,
                        'content' => '{{position}}',
                        'fontSize' => 12,
                        'fontWeight' => 'normal',
                        'textAlign' => 'left',
                        'color' => '#000000',
                    ],
                    [
                        'id' => 'serial_label_' . uniqid(),
                        'type' => 'text',
                        'x' => 10,
                        'y' => 110,
                        'width' => 80,
                        'height' => 20,
                        'content' => 'N° Série:',
                        'fontSize' => 12,
                        'fontWeight' => 'bold',
                        'textAlign' => 'left',
                        'color' => '#000000',
                    ],
                    [
                        'id' => 'serial_value_' . uniqid(),
                        'type' => 'variable',
                        'x' => 100,
                        'y' => 110,
                        'width' => 138,
                        'height' => 20,
                        'content' => '{{numero_serie}}',
                        'fontSize' => 12,
                        'fontWeight' => 'normal',
                        'textAlign' => 'left',
                        'color' => '#000000',
                    ],
                ]));
                
            default:
                return json_encode($baseElements);
        }
    }
};
