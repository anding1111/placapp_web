<?php

namespace App\Imports;

use App\Models\Plate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class PlatesImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Verificar si tenemos la información mínima necesaria
        if (empty($row['plate'])) {
            return null;
        }

        // Preparación de datos
        $plate = strtoupper(trim($row['plate']));
        $plate_level = isset($row['level']) ? (int) $row['level'] : 3;
        $plate_location = $row['location'] ?? '';
        $plate_detail = $row['detail'] ?? '';

        // Comprobar si ya existe esta placa
        $existingPlate = Plate::where('plate_name', $plate)
                             ->where('plate_enable', 1)
                             ->first();

        if (!$existingPlate) {
            // Si no existe, crear una nueva
            return new Plate([
                'plate_name' => $plate,
                'plate_level' => $plate_level,
                'plate_location' => $plate_location,
                'plate_detail' => $plate_detail,
                'plate_enable' => 1
            ]);
        }

        // Si ya existe, no hacemos nada (podrías modificar para actualizar si prefieres)
        return null;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'plate' => 'required|string|max:12',
            'level' => 'nullable|integer|min:0|max:3',
            'location' => 'nullable|string|max:50',
            'detail' => 'nullable|string|max:200',
        ];
    }
}

class DeletePlatesImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (empty($row['plate'])) {
            return null;
        }

        $plate = strtoupper(trim($row['plate']));
        
        // Buscar y marcar como eliminada la placa
        Plate::where('plate_name', $plate)
             ->where('plate_enable', 1)
             ->update(['plate_enable' => 0]);

        return null;
    }
}