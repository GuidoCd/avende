<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropertyLead;
use Inertia\Inertia;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $leads = PropertyLead::with(['property' => function($q) {
            $q->select('id', 'title');
        }])->latest()->paginate(15);

        return Inertia::render('Admin/Leads/Index', [
            'leads' => $leads
        ]);
    }
}
