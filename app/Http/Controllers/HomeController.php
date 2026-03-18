<?php

namespace App\Http\Controllers;

use App\Models\Property\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Obtener la IP del usuario
        $ip = app()->environment('local') ? '190.186.234.12' : $request->ip(); // IP de prueba si estás en localhost
        
        // 2. Coordenadas por defecto (Santa Cruz, Bolivia)
        $defaultLat = -17.7833;
        $defaultLng = -63.1821;

        // 3. Intentar obtener ubicación por IP (Fallback)
        try {
            $ipData = Http::timeout(2)->get("http://ip-api.com/json/{$ip}")->json();
            if (isset($ipData['status']) && $ipData['status'] === 'success') {
                $defaultLat = $ipData['lat'];
                $defaultLng = $ipData['lon'];
            }
        } catch (\Exception $e) {
            // Falla silenciosamente y mantiene Santa Cruz
        }

        // 4. Consulta optimizada de propiedades
        // Usamos select() para no traer datos pesados como descripciones largas si no se necesitan en el mapa
        $query = Property::query()
                            ->with('media')
                            ->select('id', 'title', 'address', 'price', 'type', 'bathrooms', 'rooms',
        //'has_360', 'beds'
        'latitude', 'longitude')
            ->where('is_active', 1); // Asumiendo que tienes un estado

        // Si el frontend envía coordenadas (por ejemplo, al mover el mapa o buscar), filtramos por radio
        if ($request->has(['lat', 'lng'])) {
            $lat = (float) $request->lat;
            $lng = (float) $request->lng;
            $radius = $request->input('radius', 10); // 10 km por defecto

            // Fórmula Haversine en SQL para buscar por distancia (Performance)
            $query->selectRaw(
                '( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance',
                [$lat, $lng, $lat]
            )
            ->having('distance', '<', $radius)
            ->orderBy('distance');
        } else {
            // Si no hay coordenadas, traemos las últimas cerca de la ubicación por defecto
            $query->orderBy('created_at', 'desc')->limit(50);
        }

        $properties = $query->get()->map(function ($property) {
            return [
                'id' => $property->id,
                'title' => $property->title,
                'address' => $property->address,
                'price' => $property->price,
                // Si el Enum se serializa raro, puedes enviar el ->value
                'type' => $property->type, 
                'bathrooms' => $property->bathrooms,
                'rooms' => $property->rooms,
                // Mapeamos para que el frontend reciba 'lat' y 'lng' como espera tu componente
                'lat' => $property->latitude,
                'lng' => $property->longitude,
                // Aquí obtenemos la imagen miniatura generada por Spatie (super rápido y ligero)
                'image' => $property->getFirstMediaUrl('images', 'thumb') ?: null, 
            ];
        });

        return Inertia::render('Welcome', [
            'initialProperties' => $properties,
            'ipLocation' => [
                'lat' => $defaultLat,
                'lng' => $defaultLng
            ],
            // Pasamos los filtros actuales para mantener el estado
            'filters' => $request->only(['lat', 'lng', 'search'])
        ]);
    }
}