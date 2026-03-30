<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Property\Property;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_users' => User::count(),
                'total_properties' => Property::count(),
                'active_properties' => Property::where('is_active', true)->count(),
                'draft_properties' => Property::where('publishing_status', 'draft')->count(),
            ]
        ]);
    }
}
