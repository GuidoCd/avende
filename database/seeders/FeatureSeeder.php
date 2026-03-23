<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property\Feature;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            // General Amenities
            ['type' => 'amenities', 'name' => ['en' => 'WiFi', 'es' => 'WiFi', 'pt' => 'WiFi']],
            ['type' => 'amenities', 'name' => ['en' => 'Air Conditioning', 'es' => 'Aire Acondicionado', 'pt' => 'Ar Condicionado']],
            ['type' => 'amenities', 'name' => ['en' => 'Heating', 'es' => 'Calefacción', 'pt' => 'Aquecimento']],
            ['type' => 'amenities', 'name' => ['en' => 'Equipped Kitchen', 'es' => 'Cocina Equipada', 'pt' => 'Cozinha Equipada']],
            ['type' => 'amenities', 'name' => ['en' => 'Furnished', 'es' => 'Amoblado', 'pt' => 'Mobilado']],
            
            // Outdoor
            ['type' => 'outdoor', 'name' => ['en' => 'Pool', 'es' => 'Piscina', 'pt' => 'Piscina']],
            ['type' => 'outdoor', 'name' => ['en' => 'Garden', 'es' => 'Jardín', 'pt' => 'Jardim']],
            ['type' => 'outdoor', 'name' => ['en' => 'Balcony', 'es' => 'Balcón', 'pt' => 'Varanda']],
            ['type' => 'outdoor', 'name' => ['en' => 'Terrace', 'es' => 'Terraza', 'pt' => 'Terraço']],
            ['type' => 'outdoor', 'name' => ['en' => 'BBQ Grill', 'es' => 'Parrillero', 'pt' => 'Churrasqueira']],
            
            // Security
            ['type' => 'security', 'name' => ['en' => '24/7 Security', 'es' => 'Seguridad 24/7', 'pt' => 'Segurança 24/7']],
            ['type' => 'security', 'name' => ['en' => 'Cameras', 'es' => 'Cámaras de Vigilancia', 'pt' => 'Câmeras']],
            ['type' => 'security', 'name' => ['en' => 'Alarm', 'es' => 'Alarma', 'pt' => 'Alarme']],
            ['type' => 'security', 'name' => ['en' => 'Electric Fence', 'es' => 'Cerca Eléctrica', 'pt' => 'Cerca Elétrica']],
            
            // Building/Condo
            ['type' => 'building', 'name' => ['en' => 'Elevator', 'es' => 'Ascensor', 'pt' => 'Elevador']],
            ['type' => 'building', 'name' => ['en' => 'Gym', 'es' => 'Gimnasio', 'pt' => 'Academia']],
            ['type' => 'building', 'name' => ['en' => 'Events Room', 'es' => 'Salón de Eventos', 'pt' => 'Salão de Festas']],
            ['type' => 'building', 'name' => ['en' => 'Pet Friendly', 'es' => 'Mascotas Permitidas', 'pt' => 'Aceita Pets']],
        ];

        foreach ($features as $feature) {
            Feature::updateOrCreate(
                ['type' => $feature['type'], 'name->en' => $feature['name']['en']], 
                $feature
            );
        }
    }
}
