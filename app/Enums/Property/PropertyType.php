<?php

namespace App\Enums\Property;

enum PropertyType: string
{
    case CASA = 'casa';
    case DEPARTAMENTO = 'departamento';
    case TERRENO = 'terreno';
    case GALPON = 'galpon';
    case LOCAL_COMERCIAL = 'local_comercial';
    case OFICINA = 'oficina';

    public function label(): string
    {
        return match($this) {
            self::CASA => 'Casa',
            self::DEPARTAMENTO => 'Departamento',
            self::TERRENO => 'Terreno',
            self::GALPON => 'Galpón',
            self::LOCAL_COMERCIAL => 'Local Comercial',
            self::OFICINA => 'Oficina',
        };
    }
}
