<?php

namespace App\Imports;

use App\Models\Plate;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Log;

class DeletePlatesImport extends DefaultValueBinder implements WithStartRow, ToCollection, WithCustomValueBinder
{
    /**
     * @return int
     */
    public function startRow(): int
    {
        return 1; // Comenzar desde la primera fila
    }
    
    /**
     * @param Cell $cell
     * @param mixed $value
     */
    public function bindValue(Cell $cell, $value)
    {
        // Asegurarse de que las celdas vacías sean null y no cadenas vacías
        if (is_string($value) && $value === '') {
            $cell->setValueExplicit(null, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NULL);
            return true;
        }
        
        return parent::bindValue($cell, $value);
    }
    
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Extraer el nombre de la placa (primera columna)
            $plate = isset($row[0]) ? strtoupper(trim($row[0])) : null;
            
            // Validar si la placa no está vacía
            if (!empty($plate)) {
                // Marcar la placa como eliminada (plate_enable = 0)
                $affected = Plate::where('plate_name', $plate)
                               ->where('plate_enable', 1)
                               ->update(['plate_enable' => 0]);
                
                // Opcional: registrar resultado
                if ($affected > 0) {
                    Log::info("Placa eliminada: $plate");
                } else {
                    Log::info("Placa no encontrada para eliminar: $plate");
                }
            }
        }
    }
}