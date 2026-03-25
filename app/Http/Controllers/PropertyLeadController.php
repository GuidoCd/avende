<?php

namespace App\Http\Controllers;

use App\Models\Property\Property;
use App\Models\PropertyLead;
use Illuminate\Http\Request;

class PropertyLeadController extends Controller
{
    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:2000',
        ]);

        $property->leads()->create([
            'user_id' => $request->user()?->id,
            'name' => $request->user()?->name ?? $validated['name'] ?? 'Sin Nombre',
            'email' => $request->user()?->email ?? $validated['email'] ?? 'sin@correo.com',
            'phone' => $validated['phone'] ?? null,
            'message' => $validated['message'],
        ]);

        return response()->json(['message' => 'Lead created successfully']);
    }
}
