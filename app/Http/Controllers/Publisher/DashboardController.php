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
        // Placeholder data for publisher's dashboard analytics
        $stats = [
            'total_properties' => 12,
            'active_properties' => 9,
            'total_visits' => 12450,
            'total_favorites' => 342,
        ];

        // Fake data for a 7-day visits chart
        $visitsChart = [
            'labels' => ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
            'data' => [120, 200, 150, 80, 250, 400, 310]
        ];

        return Inertia::render('Publisher/Dashboard', [
            'stats' => $stats,
            'visitsChart' => $visitsChart
        ]);
    }
}
