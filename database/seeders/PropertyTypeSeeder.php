<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property\PropertyType;

class PropertyTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'slug' => 'house',
                'name' => ['en' => 'House', 'es' => 'Casa', 'pt' => 'Casa'],
                'icon' => 'home'
            ],
            [
                'slug' => 'apartment',
                'name' => ['en' => 'Apartment', 'es' => 'Departamento', 'pt' => 'Apartamento'],
                'icon' => 'building'
            ],
            [
                'slug' => 'land',
                'name' => ['en' => 'Land', 'es' => 'Terreno', 'pt' => 'Terreno'],
                'icon' => 'map'
            ],
            [
                'slug' => 'commercial',
                'name' => ['en' => 'Commercial', 'es' => 'Local Comercial', 'pt' => 'Ponto Comercial'],
                'icon' => 'store'
            ],
            [
                'slug' => 'office',
                'name' => ['en' => 'Office', 'es' => 'Oficina', 'pt' => 'Escritório'],
                'icon' => 'briefcase'
            ],
            [
                'slug' => 'warehouse',
                'name' => ['en' => 'Warehouse', 'es' => 'Galpón', 'pt' => 'Armazém'],
                'icon' => 'warehouse'
            ]
        ];

        foreach ($types as $type) {
            PropertyType::updateOrCreate(['slug' => $type['slug']], $type);
        }
    }
}
