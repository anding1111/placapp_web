<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plate;
use App\Models\PlateDemo;
use App\Imports\PlatesImport;
use App\Imports\DeletePlatesImport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class PlateController extends Controller
{
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
            
            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }
            
            return back()->with('success', 'Archivo importado correctamente');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => $e->getMessage()]);
            }
            
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
            
            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }
            
            return back()->with('success', 'Placas eliminadas correctamente');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => $e->getMessage()]);
            }
            
            return back()->with('error', 'Error al procesar: ' . $e->getMessage());
        }
    }
    
    /**
     * API para DataTables - Obtener placas
     */
    public function fetchDataTable(Request $request)
    {
        $user = auth()->user();
        
        // Usar PlateDemo para usuarios demo (level 4), Plate para usuarios regulares
        if ($user->isDemoUser()) {
            $plates = PlateDemo::where('plate_enable', 1);
        } else {
            $plates = Plate::where('plate_enable', 1);
        }
        
        return DataTables::of($plates)
            ->addIndexColumn()
            ->editColumn('plate_entry_date', function ($plate) {
                // Formatear fecha: 2024-12-13 18:16:35
                return $plate->plate_entry_date ? 
                    Carbon::parse($plate->plate_entry_date)->format('Y-m-d H:i:s') : 
                    '';
            })
            ->toJson();
    }
    
    /**
     * Obtener información de una placa específica para el modal
     */
    public function fetchPlate(Request $request)
    {
        $plateId = $request->input('plateId');
        $user = auth()->user();
        
        // Consultar la tabla correcta según el tipo de usuario
        if ($user->isDemoUser()) {
            $plate = PlateDemo::where('id', $plateId)
                         ->where('plate_enable', 1)
                         ->first();
        } else {
            $plate = Plate::where('id', $plateId)
                         ->where('plate_enable', 1)
                         ->first();
        }
                     
        if (!$plate) {
            return '<p class="text-center" style="color: rgba(255,255,255,0.6);">No se encontró la placa</p>';
        }
        
        // Estilo simplificado para Alerta Nativa iOS
        $html = '<input type="hidden" id="numInvoice" value="'.$plateId.'">';
        $html .= '<p class="text-center" style="font-size: 16px; font-weight: 500; color: #fff; margin-bottom: 5px;">';
        $html .= 'PLACA: <strong style="color: #007aff;">'.$plate->plate_name.'</strong>';
        $html .= '</p>';
        $html .= '<p class="text-center" style="font-size: 12px; color: rgba(255,255,255,0.5);">';
        $html .= 'Entrada: '.$plate->plate_entry_date;
        $html .= '</p>';
        
        return $html;
    }
    
    /**
     * Elimina una placa específica 
     */
    public function nullPlate(Request $request)
    {
        $plateId = $request->input('invId');
        $user = auth()->user();
        
        if ($plateId) {
            // Actualizar en la tabla correcta según el tipo de usuario
            if ($user->isDemoUser()) {
                PlateDemo::where('id', $plateId)
                     ->where('plate_enable', 1)
                     ->update(['plate_enable' => 0]);
            } else {
                Plate::where('id', $plateId)
                     ->where('plate_enable', 1)
                     ->update(['plate_enable' => 0]);
            }
                 
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'ID de placa no proporcionado']);
    }
    
    /**
     * Buscar placas 
     */
    public function searchPlates(Request $request)
    {
        $keyword = $request->input('keyword');
        $user = auth()->user();
        
        if (empty($keyword)) {
            return '';
        }
        
        // Buscar en la tabla correcta según el tipo de usuario
        if ($user->isDemoUser()) {
            $plates = PlateDemo::where('plate_enable', 1)
                          ->where('plate_level', '>=', $user->level)
                          ->where('plate_name', 'like', $keyword . '%')
                          ->orderBy('plate_name')
                          ->get();
        } else {
            $plates = Plate::where('plate_enable', 1)
                          ->where('plate_level', '>=', $user->level)
                          ->where('plate_name', 'like', $keyword . '%')
                          ->orderBy('plate_name')
                          ->get();
        }
                      
        return view('plates.search_results', compact('plates', 'user'));
    }
}