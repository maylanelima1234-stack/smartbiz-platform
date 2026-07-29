<?php

namespace App\Http\Controllers;

use App\Core\Automation\WorkflowEngine;
use App\Core\Context\PlatformContext;
use App\Domain\CRM\Models\Lead;
use App\Models\SmartWorkflow;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AutomationController extends Controller
{
    public function __construct(private readonly PlatformContext $context) {}
    public function index(): View
    {
        $workflows=SmartWorkflow::query()->where('company_id',$this->context->companyId())->withCount('runs')->latest()->get();
        return view('automations.index',compact('workflows'));
    }
    public function create(): View { return view('automations.create'); }
    public function store(Request $request): RedirectResponse
    {
        $data=$request->validate(['name'=>'required|string|max:120','description'=>'nullable|string|max:500','trigger'=>'required|in:lead.created,lead.updated,lead.moved','condition_field'=>'nullable|string|max:80','condition_operator'=>'nullable|string|max:30','condition_value'=>'nullable|string|max:255','action_type'=>'required|in:notification,set_priority,create_follow_up','action_title'=>'nullable|string|max:120','action_message'=>'nullable|string|max:500','action_value'=>'nullable|string|max:120','action_days'=>'nullable|integer|min:1|max:365']);
        $conditions=[]; if(filled($data['condition_field']??null)) $conditions[]=['field'=>$data['condition_field'],'operator'=>$data['condition_operator']?:'equals','value'=>$data['condition_value']??null];
        $action=['type'=>$data['action_type']];
        if($data['action_type']==='notification') $action+=['title'=>$data['action_title']?:'Automação SmartBiz','message'=>$data['action_message']?:'A automação foi executada para {lead}.'];
        if($data['action_type']==='set_priority') $action+=['value'=>$data['action_value']?:'high'];
        if($data['action_type']==='create_follow_up') $action+=['title'=>$data['action_title']?:'Follow-up automático','description'=>$data['action_message']?:'Criado pelo SmartBiz Automation.','days'=>(int)($data['action_days']?:1)];
        SmartWorkflow::create(['company_id'=>$this->context->companyId(),'created_by'=>$request->user()->id,'name'=>$data['name'],'description'=>$data['description']??null,'trigger'=>$data['trigger'],'conditions'=>$conditions,'actions'=>[$action],'is_active'=>true]);
        return redirect()->route('automations.index')->with('success','Automação criada com sucesso.');
    }
    public function toggle(SmartWorkflow $workflow): RedirectResponse { $this->guard($workflow); $workflow->update(['is_active'=>!$workflow->is_active]); return back()->with('success','Status atualizado.'); }
    public function destroy(SmartWorkflow $workflow): RedirectResponse { $this->guard($workflow); $workflow->delete(); return back()->with('success','Automação removida.'); }
    public function test(Request $request, SmartWorkflow $workflow, WorkflowEngine $engine): RedirectResponse
    {
        $this->guard($workflow); $lead=Lead::query()->where('company_id',$this->context->companyId())->latest()->first();
        if(!$lead) return back()->with('error','Cadastre um lead antes de testar.');
        $run=$engine->execute($workflow,$lead,['manual_test'=>true,'user_id'=>$request->user()->id]);
        return back()->with($run->status==='failed'?'error':'success','Teste concluído: '.$run->status.'.');
    }
    private function guard(SmartWorkflow $workflow): void { abort_unless((int)$workflow->company_id===(int)$this->context->companyId(),404); }
}
