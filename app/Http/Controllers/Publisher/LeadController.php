<?php

namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use App\Models\PropertyLead;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = PropertyLead::with('property:id,uuid,title,slug')
            ->whereHas('property', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('property', function ($qProperty) use ($search) {
                      $qProperty->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $leads = $query->latest()->paginate(15)->withQueryString();

        return Inertia::render('Publisher/Leads/Index', [
            'leads' => $leads,
            'filters' => $request->only('search')
        ]);
    }
}
