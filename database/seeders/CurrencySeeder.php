<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            ['code' => 'BOB', 'name' => 'Boliviano', 'symbol' => 'Bs.'],
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$'],
            ['code' => 'ARS', 'name' => 'Peso Argentino', 'symbol' => '$'],
        ];

        foreach ($currencies as $currency) {
            \App\Models\Currency::updateOrCreate(['code' => $currency['code']], $currency);
        }
    }
}
