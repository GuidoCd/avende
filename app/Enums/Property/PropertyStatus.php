<?php

namespace App\Enums\Property;

enum PropertyStatus: string
{
    case VENTA = 'venta';
    case ALQUILER = 'alquiler';
    case ANTICRETICO = 'anticretico';

    public function label(): string
    {
        return match($this) {
            self::VENTA => 'Venta',
            self::ALQUILER => 'Alquiler',
            self::ANTICRETICO => 'Anticrético',
        };
    }
}
