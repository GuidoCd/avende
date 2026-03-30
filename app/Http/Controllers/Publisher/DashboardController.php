<?php

namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the publisher's analytics dashboard.
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        // Base query for properties belonging to the publisher
        $baseQuery = \App\Models\Property\Property::where('user_id', $userId);
        
        $stats = [
            'total_properties' => (clone $baseQuery)->count(),
            'active_properties' => (clone $baseQuery)->where('is_active', true)->where('publishing_status', 'published')->count(),
            'total_visits' => (clone $baseQuery)->sum('views_count') ?? 0,
            'total_leads' => \App\Models\PropertyLead::whereHas('property', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->count(),
        ];

        // Fake data for a 7-day visits chart (Needs tracking table to be real)
        $visitsChart = [
            'labels' => ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
            'data' => [0, 0, 0, 0, 0, 0, 0]
        ];

        return Inertia::render('Publisher/Dashboard', [
            'stats' => $stats,
            'visitsChart' => $visitsChart
        ]);
    }
}
