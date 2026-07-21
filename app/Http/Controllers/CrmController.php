<?php

namespace App\Http\Controllers;

use App\Models\CrmPipeline;
use App\Models\Lead;

class CrmController extends Controller
{
    public function index()
    {
        $totalLeads = Lead::count();
        $openLeads = Lead::whereNotIn('status', ['Convertido','Perdido'])->count();
        $convertedLeads = Lead::where('status', 'Convertido')->count();
        $pipeline = CrmPipeline::with(['stages.leads.company'])->first();

        return view('crm.index', compact('totalLeads','openLeads','convertedLeads','pipeline'));
    }
}
