<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Printer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PrinterController extends Controller
{
    /**
     * Afficher la liste des imprimantes
     */
    public function index()
{
    file_put_contents(storage_path('logs/printer_debug.log'), date('Y-m-d H:i:s') . " - Printer index method\n", FILE_APPEND);
    file_put_contents(storage_path('logs/printer_debug.log'), "User connected: " . (auth()->check() ? auth()->user()->name : 'Not logged in') . "\n", FILE_APPEND);
    file_put_contents(storage_path('logs/printer_debug.log'), "User role: " . (auth()->check() ? auth()->user()->role : 'No role') . "\n", FILE_APPEND);

    $printers = Printer::all();
    return view('printers.index', compact('printers'));
}

    /**
     * Afficher le formulaire de création d'une imprimante
     */
    public function create()
    {
        return view('printers.create');
    }

    /**
     * Enregistrer une nouvelle imprimante
     */
    public function store(Request $request)
{
    $validationRules = [
        'name' => 'required|string|max:255',
        'model' => 'required|string|max:255',
        'connection_type' => 'nullable|string',
        'ip_address' => 'nullable|string|max:45',
        'is_default' => 'boolean',
        'dpi' => 'nullable|numeric',
    ];

    // Ajouter des règles conditionnelles pour les dimensions
    if ($request->input('label_format') === 'custom') {
        $validationRules['label_width'] = 'required|numeric|min:1';
        $validationRules['label_height'] = 'required|numeric|min:1';
    } else {
        $validationRules['label_width'] = 'nullable|numeric';
        $validationRules['label_height'] = 'nullable|numeric';
    }

    // Effectuer la validation
    $validatedData = $request->validate($validationRules);

    // Si cette imprimante est définie comme par défaut, retirer le statut par défaut des autres
    if ($request->has('is_default') && $request->is_default) {
        Printer::where('is_default', true)->update(['is_default' => false]);
    }

    // Créer l'imprimante
    $printer = Printer::create($validatedData);

    return redirect()->route('printers.index')
        ->with('success', 'Imprimante ajoutée avec succès');
}

    /**
     * Afficher une imprimante spécifique
     */
    public function show(Printer $printer)
    {
        return view('printers.show', compact('printer'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Printer $printer)
    {
        return view('printers.edit', compact('printer'));
    }

    /**
     * Mettre à jour une imprimante
     */
    public function update(Request $request, Printer $printer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'ip_address' => 'nullable|string|max:45',
            'port' => 'nullable|numeric',
            'label_width' => 'required|numeric',
            'label_height' => 'required|numeric',
            'is_default' => 'boolean'
        ]);

        // Si cette imprimante est définie comme par défaut, retirer le statut par défaut des autres
        if ($request->has('is_default') && $request->is_default) {
            Printer::where('id', '!=', $printer->id)
                  ->where('is_default', true)
                  ->update(['is_default' => false]);
        }

        $printer->update($request->all());

        return redirect()->route('printers.index')
            ->with('success', 'Imprimante mise à jour avec succès');
    }

    /**
     * Supprimer une imprimante
     */
    public function destroy(Printer $printer)
    {
        $printer->delete();

        return redirect()->route('printers.index')
            ->with('success', 'Imprimante supprimée avec succès');
    }

    /**
     * Tester l'impression
     */
    public function testPrint(Printer $printer)
    {
        // Stockage des informations d'imprimante en session pour le test
        Session::put('test_printer', $printer->toArray());
        
        return view('printers.test_print', compact('printer'));
    }

    /**
     * Définir une imprimante comme par défaut
     */
    public function setDefault(Printer $printer)
    {
        Printer::where('is_default', true)->update(['is_default' => false]);
        $printer->update(['is_default' => true]);

        return redirect()->route('printers.index')
            ->with('success', 'Imprimante "' . $printer->name . '" définie comme imprimante par défaut');
    }

    public function updatePrinterStatus()
{
    $printers = Printer::all();
    
    foreach ($printers as $printer) {
        $status = $printer->isAvailable() ? 'online' : 'offline';
        $printer->update(['status' => $status]);
    }

    return redirect()->route('printers.index')
        ->with('success', 'Statut des imprimantes mis à jour');
}
}