<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\Property\Property;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::with('user')->latest()->paginate(15);
        return Inertia::render('Admin/Properties/Index', [
            'properties' => $properties
        ]);
    }
}
