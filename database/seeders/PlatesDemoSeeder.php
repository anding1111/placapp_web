<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PlateDemo;

class PlatesDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lista ampliada de ciudades principales e intermedias de Colombia
        $cities = [
            'Bogotá', 'Medellín', 'Cali', 'Barranquilla', 'Cartagena', 
            'Bucaramanga', 'Pereira', 'Santa Marta', 'Ibagué', 'Cúcuta',
            'Manizales', 'Neiva', 'Villavicencio', 'Pasto', 'Montería',
            'Valledupar', 'Popayán', 'Armenia', 'Sincelejo', 'Tunja'
        ];

        // Lista ampliada de descripciones/motivos
        $descriptions = [
            'Vehículo sospechoso',
            'Reporte de hurto',
            'Accidente de tránsito',
            'Vehículo abandonado',
            'Infracción de tránsito',
            'Vehículo de interés',
            'Reporte ciudadano',
            'Control rutinario',
            'Vehículo en investigación',
            'Alerta de seguridad',
            'Exceso de velocidad',
            'Vehículo sin SOAT',
            'Evasión de peaje',
            'Mal estacionado',
            'Pico y placa',
            'Involucrado en fleteo',
            'Placa adulterada',
            'Transporte ilegal',
            'Ruido excesivo',
            'Emisiones contaminantes'
        ];
        
        // Lista ampliada de detalles visuales para dar variedad
        $details = [
            'Sedan color blanco', 'Camioneta color negro', 'Automóvil color rojo',
            'Vehículo color gris', 'Camioneta color azul', 'Sedan color plateado',
            'Automóvil color verde', 'Vehículo color amarillo', 'Camioneta color café',
            'Sedan color negro', 'Moto color negro', 'Moto color azul',
            'Taxi marca Hyundai', 'Taxi marca Kia', 'Bus escolar',
            'Camión de carga pequeño', 'Automóvil deportivo rojo', 'Camioneta 4x4 gris',
            'Sedan color vino tinto', 'Vehículo compacto azul', 'Moto de alto cilindraje',
            'Furgoneta blanca', 'Automóvil clásico beige', 'Camioneta platón blanca',
            'Vehículo con vidrios polarizados'
        ];

        // Generar 500 placas demo
        for ($i = 0; $i < 500; $i++) {
            // Generación de placa:
            // 80% Probabilidad de formato nuevo/estándar (ABC123)
            // 20% Probabilidad de formato moto/antiguo (ABC12D) para variedad
            $letters = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90));
            
            if (rand(1, 100) <= 80) {
                $numbers = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
                $plateName = $letters . $numbers;
            } else {
                $numbers = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
                $lastChar = chr(rand(65, 90)); // Letra al final
                $plateName = $letters . $numbers . $lastChar;
            }
            
            // Fecha aleatoria con variación de horas para que no queden todas a la misma hora exacta
            $daysAgo = rand(0, 180);
            $entryDate = now()->subDays($daysAgo)->subHours(rand(1, 23))->subMinutes(rand(0, 59));
            
            PlateDemo::create([
                'plate_name' => $plateName,
                'plate_desc' => $descriptions[array_rand($descriptions)],
                'plate_entry_date' => $entryDate,
                'plate_exit_date' => null, // Se mantiene null como en el patrón original
                'plate_enable' => true,
                'plate_level' => 4, // Demo level
                'plate_location' => $cities[array_rand($cities)],
                'plate_detail' => $details[array_rand($details)],
            ]);
        }
    }
}