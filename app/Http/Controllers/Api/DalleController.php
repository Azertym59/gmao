<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dalle;
use Illuminate\Http\Request;

class DalleController extends Controller
{
    /**
     * Mettre à jour le numéro de série d'une dalle
     */
    public function updateNumero(Request $request, $id)
    {
        $dalle = Dalle::findOrFail($id);
        
        $request->validate([
            'numero_dalle' => 'nullable|string|max:255',
        ]);
        
        $dalle->update([
            'numero_dalle' => $request->numero_dalle,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Numéro de dalle mis à jour avec succès',
            'data' => [
                'id' => $dalle->id,
                'numero_dalle' => $dalle->numero_dalle,
            ]
        ]);
    }
}
