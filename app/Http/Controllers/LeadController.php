<?php

namespace App\Http\Controllers;

use App\Domain\CRM\Models\CrmPipeline;
use App\Domain\CRM\Models\CrmStage;
use App\Domain\CRM\Models\Lead;
use App\Models\Company;
use App\Models\SmartNotification;
use App\Models\User;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $leads = Lead::with(['company','stage','assignedUser'])
            ->when($search, function($query) use ($search){
                $query->where('name','like',"%{$search}%")
                    ->orWhere('email','like',"%{$search}%")
                    ->orWhere('phone','like',"%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('leads.index', compact('leads','search'));
    }

    public function create()
    {
        $companies = Company::orderBy('name')->get();
        $pipelines = CrmPipeline::orderBy('name')->get();
        $stages = CrmStage::orderBy('position')->get();
        $users = User::orderBy('name')->get();

        return view('leads.create', compact('companies','pipelines','stages','users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'pipeline_id' => 'nullable|exists:crm_pipelines,id',
            'stage_id' => 'nullable|exists:crm_stages,id',
            'assigned_user_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'source' => 'nullable|string|max:100',
            'campaign' => 'nullable|string|max:255',
            'value' => 'nullable|numeric|min:0',
            'status' => 'required|string|max:50',
            'priority' => 'required|string|max:50',
            'notes' => 'nullable|string',
            'next_follow_up_at' => 'nullable|date',
        ]);

        $lead = Lead::create($data);

        SmartNotification::pushFor(
            $request->user(),
            'Novo lead cadastrado',
            $lead->name . ' foi adicionado ao CRM.',
            'lead',
            route('leads.show', $lead),
            'L'
        );

        return redirect()->route('leads.index')->with('success','Lead cadastrado com sucesso!');
    }

    public function show(Lead $lead)
    {
        $lead->load(['company','pipeline','stage','assignedUser']);
        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $companies = Company::orderBy('name')->get();
        $pipelines = CrmPipeline::orderBy('name')->get();
        $stages = CrmStage::orderBy('position')->get();
        $users = User::orderBy('name')->get();

        return view('leads.edit', compact('lead','companies','pipelines','stages','users'));
    }

    public function update(Request $request, Lead $lead)
    {
        $data = $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'pipeline_id' => 'nullable|exists:crm_pipelines,id',
            'stage_id' => 'nullable|exists:crm_stages,id',
            'assigned_user_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'source' => 'nullable|string|max:100',
            'campaign' => 'nullable|string|max:255',
            'value' => 'nullable|numeric|min:0',
            'status' => 'required|string|max:50',
            'priority' => 'required|string|max:50',
            'notes' => 'nullable|string',
            'next_follow_up_at' => 'nullable|date',
        ]);

        $lead->update($data);

        return redirect()->route('leads.index')->with('success','Lead atualizado com sucesso!');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('leads.index')->with('success','Lead removido com sucesso!');
    }
}
