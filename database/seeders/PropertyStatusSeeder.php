<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property\PropertyStatus;

class PropertyStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            [
                'slug' => 'sale',
                'name' => ['en' => 'For Sale', 'es' => 'Venta', 'pt' => 'Venda'],
                'color' => 'blue'
            ],
            [
                'slug' => 'rent',
                'name' => ['en' => 'For Rent', 'es' => 'Alquiler', 'pt' => 'Aluguel'],
                'color' => 'green'
            ],
            [
                'slug' => 'anticretico',
                'name' => ['en' => 'Anticrético', 'es' => 'Anticrético', 'pt' => 'Anticrético'],
                'color' => 'orange'
            ]
        ];

        foreach ($statuses as $status) {
            PropertyStatus::updateOrCreate(['slug' => $status['slug']], $status);
        }
    }
}
