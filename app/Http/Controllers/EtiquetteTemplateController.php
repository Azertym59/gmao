<?php

namespace App\Http\Controllers;

use App\Models\EtiquetteTemplate;
use App\Models\Printer;
use App\Services\EtiquettePrintService;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EtiquetteTemplateController extends Controller
{
    protected $printService;
    protected $qrCodeService;

    public function __construct(EtiquettePrintService $printService, QrCodeService $qrCodeService)
    {
        $this->printService = $printService;
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Display a listing of the templates.
     */
    public function index()
    {
        $templates = EtiquetteTemplate::all();
        return view('admin.etiquettes.index', compact('templates'));
    }

    /**
     * Show the form for creating a new template.
     */
    public function create()
    {
        return view('admin.etiquettes.create');
    }

    /**
     * Store a newly created template in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:chantier,dalle,module',
            'width' => 'required|numeric|min:10|max:500',
            'height' => 'required|numeric|min:10|max:500',
            'size_type' => 'required|string|in:fixed,continuous',
            'is_default' => 'sometimes|boolean',
            'template_data' => 'required|json',
        ]);

        // If this is set to default, unset any existing default for this type
        if (!empty($validated['is_default'])) {
            EtiquetteTemplate::where('type', $validated['type'])
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $template = EtiquetteTemplate::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'width' => $validated['width'],
            'height' => $validated['height'],
            'size_type' => $validated['size_type'],
            'is_default' => !empty($validated['is_default']),
            'template_data' => $validated['template_data'],
        ]);

        return redirect()->route('admin.etiquettes.index')
            ->with('success', 'Modèle d\'étiquette créé avec succès.');
    }

    /**
     * Display the specified template.
     */
    public function show(EtiquetteTemplate $template)
    {
        return view('admin.etiquettes.show', compact('template'));
    }

    /**
     * Show the form for editing the specified template.
     */
    public function edit(EtiquetteTemplate $template)
    {
        return view('admin.etiquettes.edit', compact('template'));
    }

    /**
     * Update the specified template in storage.
     */
    public function update(Request $request, EtiquetteTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:chantier,dalle,module',
            'width' => 'required|numeric|min:10|max:500',
            'height' => 'required|numeric|min:10|max:500',
            'size_type' => 'required|string|in:fixed,continuous',
            'is_default' => 'sometimes|boolean',
            'template_data' => 'required|json',
        ]);

        // If this is set to default, unset any existing default for this type
        if (!empty($validated['is_default']) && !$template->is_default) {
            EtiquetteTemplate::where('type', $validated['type'])
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $template->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'width' => $validated['width'],
            'height' => $validated['height'],
            'size_type' => $validated['size_type'],
            'is_default' => !empty($validated['is_default']),
            'template_data' => $validated['template_data'],
        ]);

        return redirect()->route('admin.etiquettes.index')
            ->with('success', 'Modèle d\'étiquette mis à jour avec succès.');
    }

    /**
     * Remove the specified template from storage.
     */
    public function destroy(EtiquetteTemplate $template)
    {
        // Check if it's the last default template for this type
        $isLastDefault = $template->is_default && 
            EtiquetteTemplate::where('type', $template->type)
                ->where('is_default', true)
                ->count() === 1;

        if ($isLastDefault) {
            return redirect()->route('admin.etiquettes.index')
                ->with('error', 'Impossible de supprimer le dernier modèle par défaut pour ce type d\'étiquette.');
        }

        $template->delete();

        return redirect()->route('admin.etiquettes.index')
            ->with('success', 'Modèle d\'étiquette supprimé avec succès.');
    }

    /**
     * Set the template as the default for its type.
     */
    public function setDefault(Request $request, EtiquetteTemplate $template)
    {
        // Unset current default for this type
        EtiquetteTemplate::where('type', $template->type)
            ->where('is_default', true)
            ->update(['is_default' => false]);

        // Set this template as default
        $template->update(['is_default' => true]);

        return redirect()->route('admin.etiquettes.index')
            ->with('success', 'Modèle défini par défaut avec succès.');
    }

    /**
     * Preview the template with sample data.
     */
    public function preview(EtiquetteTemplate $template)
    {
        // Generate example data based on template type
        $exampleData = $this->generateExampleData($template->type);
        
        // Render the template with example data
        $renderedTemplate = $this->printService->renderTemplate($template, $exampleData);
        
        return view('admin.etiquettes.preview', compact('template', 'renderedTemplate'));
    }

    /**
     * Test print the template.
     */
    public function testPrint(Request $request, EtiquetteTemplate $template)
    {
        $printer = Printer::where('is_label_printer', true)->first();

        if (!$printer) {
            return redirect()->back()->with('error', 'Aucune imprimante d\'étiquettes configurée.');
        }

        // Generate example data based on template type
        $exampleData = $this->generateExampleData($template->type);
        
        try {
            // Print the template with example data
            $result = $this->printService->printTemplate($template, $exampleData, $printer);
            
            if ($result) {
                return redirect()->back()->with('success', 'Impression de test envoyée avec succès.');
            } else {
                return redirect()->back()->with('error', 'Échec de l\'impression de test.');
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'impression de test: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Duplicate the specified template.
     */
    public function duplicate(EtiquetteTemplate $template)
    {
        $newTemplate = $template->replicate();
        $newTemplate->name = $template->name . ' (copie)';
        $newTemplate->is_default = false;
        $newTemplate->save();

        return redirect()->route('admin.etiquettes.edit', $newTemplate)
            ->with('success', 'Modèle dupliqué avec succès. Vous pouvez maintenant le modifier.');
    }

    /**
     * Get the list of available variables for a template type.
     */
    public function getVariables($type)
    {
        // Create a temporary template with the requested type
        $template = new EtiquetteTemplate(['type' => $type]);
        $variables = $template->getAvailableVariables();
        return response()->json($variables);
    }

    /**
     * Render a preview of the template with submitted data.
     */
    public function renderPreview(Request $request)
    {
        $validated = $request->validate([
            'template_data' => 'required|json',
            'type' => 'required|string|in:chantier,dalle,module',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
        ]);

        // Create a temporary template
        $tempTemplate = new EtiquetteTemplate([
            'template_data' => $validated['template_data'],
            'type' => $validated['type'],
            'width' => $validated['width'],
            'height' => $validated['height'],
        ]);

        // Generate example data
        $exampleData = $this->generateExampleData($validated['type']);
        
        // Render the template with example data
        $renderedHtml = $this->printService->renderTemplate($tempTemplate, $exampleData);
        
        return response()->json(['html' => $renderedHtml]);
    }

    /**
     * Get the list of available printers for labels.
     */
    public function getPrinters()
    {
        $printers = Printer::where('is_label_printer', true)->get();
        return response()->json($printers);
    }

    /**
     * Generate example data for preview and test print.
     */
    private function generateExampleData($type)
    {
        $data = [
            'date' => now()->format('d/m/Y'),
            'datetime' => now()->format('d/m/Y H:i'),
            'qrcode_url' => $this->qrCodeService->generateQrCodeImage('https://example.com/demo', 300),
        ];

        // Use the model's getAvailableVariables method to get all possible variables
        $template = new EtiquetteTemplate(['type' => $type]);
        $variables = $template->getAvailableVariables();
        
        // Add example values for all specific variables
        if (isset($variables['specific'])) {
            foreach ($variables['specific'] as $key => $variable) {
                if (isset($variable['example'])) {
                    $data[$key] = $variable['example'];
                }
            }
        }
        
        // Add some dynamic data specific to each type
        switch ($type) {
            case 'chantier':
                $data['reference'] = 'CHANT-' . rand(1000, 9999);
                $data['client'] = 'Client Example SA';
                break;
                
            case 'dalle':
                $data['numero'] = 'D' . rand(1, 99);
                $data['flightcase'] = 'FC-' . rand(10, 99);
                break;
                
            case 'module':
                $data['position'] = 'R' . rand(1, 9) . 'C' . rand(1, 9);
                $data['numero_serie'] = 'SN-' . Str::random(8);
                break;
        }

        return $data;
    }

}
