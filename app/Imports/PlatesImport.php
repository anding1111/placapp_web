<?php

namespace App\Imports;

use App\Models\Plate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Log;

class PlatesImport extends DefaultValueBinder implements WithStartRow, ToCollection, WithCustomValueBinder
{
    public function startRow(): int
    {
        return 1; // Comenzar desde la primera fila
    }
    
    public function bindValue(Cell $cell, $value)
    {
        // Asegurarse de que las celdas vacías sean null y no cadenas vacías
        if (is_string($value) && $value === '') {
            $cell->setValueExplicit(null, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NULL);
            return true;
        }
        
        return parent::bindValue($cell, $value);
    }
    
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Extraer los datos de la fila con las mejoras solicitadas
            $plate = isset($row[0]) ? strtoupper(str_replace(' ', '', trim($row[0]))) : null;
            $plate_level = isset($row[1]) ? str_replace(' ', '', trim($row[1])) : null;
            $plate_location = isset($row[2]) ? trim($row[2]) : null;
            $plate_detail = isset($row[3]) ? trim($row[3]) : null;
            
            // Validar si los datos mínimos están presentes, considerando 0 como válido
            // Usamos !== null para que "0" no sea considerado vacío
            if ($plate !== null && $plate !== '' && $plate_level !== null) {
                // Verificar si la placa ya existe
                $existingPlate = Plate::where('plate_name', $plate)
                                    ->where('plate_enable', 1)
                                    ->first();
                
                if (!$existingPlate) {
                    // Crear nueva placa si no existe
                    Plate::create([
                        'plate_name' => $plate,
                        'plate_level' => $plate_level,
                        'plate_location' => $plate_location ?? '',
                        'plate_detail' => $plate_detail ?? '',
                        'plate_enable' => 1
                    ]);
                    
                    Log::info("Placa importada: $plate");
                }
            } else {
                // Solo registrar error si la fila no está completamente vacía
                if ($plate !== null || $plate_level !== null) {
                    Log::error("Error: La placa o el nivel están vacíos. Placa: $plate, Nivel: $plate_level");
                }
            }
        }
    }
}