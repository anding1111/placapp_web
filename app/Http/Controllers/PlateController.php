<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plate;
use App\Imports\PlatesImport;
use App\Imports\DeletePlatesImport;
use Maatwebsite\Excel\Facades\Excel;

class PlateController extends Controller
{
    // ... otros métodos
    
    /**
     * Muestra el formulario para cargar archivo Excel
     */
    public function showUploadForm()
    {
        return view('plates.upload');
    }
    
    /**
     * Procesa la carga del archivo Excel con placas
     */
    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel' => 'required|file|mimes:xlsx,xls,csv'
        ]);
        
        try {
            Excel::import(new PlatesImport, $request->file('excel'));
            
            return back()->with('success', 'Archivo importado correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al importar: ' . $e->getMessage());
        }
    }
    
    /**
     * Muestra el formulario para eliminar placas
     */
    public function showDeleteForm()
    {
        return view('plates.delete');
    }
    
    /**
     * Procesa la eliminación de placas desde Excel
     */
    public function deleteExcel(Request $request)
    {
        $request->validate([
            'excel' => 'required|file|mimes:xlsx,xls,csv'
        ]);
        
        try {
            Excel::import(new DeletePlatesImport, $request->file('excel'));
            
            return back()->with('success', 'Placas eliminadas correctamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar: ' . $e->getMessage());
        }
    }
    
    /**
     * Elimina una placa específica (null-plate.php)
     */
    public function nullPlate(Request $request)
    {
        $plateId = $request->input('invId');
        
        if ($plateId) {
            Plate::where('plate_id', $plateId)
                 ->where('plate_enable', 1)
                 ->update(['plate_enable' => 0]);
                 
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'ID de placa no proporcionado']);
    }
    
    /**
     * Buscar placas (list_of_plate.php)
     */
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $user = auth()->user();
        
        if (empty($keyword)) {
            return '';
        }
        
        $plates = Plate::where('plate_enable', 1)
                      ->where('plate_level', '>=', $user->level)
                      ->where('plate_name', 'like', $keyword . '%')
                      ->orderBy('plate_name')
                      ->get();
                      
        return view('plates.search_results', compact('plates', 'user'));
    }
}