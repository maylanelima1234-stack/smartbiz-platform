<?php
namespace App\Domain\CRM\Http\Controllers;
use App\Core\Authorization\Enums\Permission;
use App\Core\Authorization\Facades\SmartGate;
use App\Core\Context\PlatformContext;
use App\Core\Licensing\Enums\Feature;
use App\Core\Licensing\Facades\SmartLicense;
use App\Domain\CRM\Models\CrmPipeline;
use App\Domain\CRM\Models\Lead;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
class CrmLeadPageController extends Controller
{
    public function __construct(private readonly PlatformContext $context) {}
    public function index(Request $request): View
    {
        abort_unless(SmartLicense::allows(Feature::CRM),403); SmartGate::authorize(Permission::CRM_VIEW);
        $search=$request->string('search')->toString();
        $leads=Lead::query()->where('company_id',$this->context->companyId())->with(['stage','owner'])
            ->when($search,fn($q)=>$q->where(fn($q)=>$q->where('name','like',"%{$search}%")->orWhere('email','like',"%{$search}%")->orWhere('phone','like',"%{$search}%")))
            ->latest()->paginate(20)->withQueryString();
        return view('crm.leads.index',compact('leads','search'));
    }
    public function show(Lead $lead): View
    {
        abort_unless(SmartLicense::allows(Feature::CRM),403); SmartGate::authorize(Permission::CRM_VIEW);
        abort_unless((int)$lead->company_id===(int)$this->context->companyId(),404);
        return view('crm.leads.show',['lead'=>$lead->load(['pipeline','stage','owner','activities.user'])]);
    }
    public function kanban(): View
    {
        abort_unless(SmartLicense::allows(Feature::CRM),403); SmartGate::authorize(Permission::CRM_VIEW);
        $pipelines=CrmPipeline::query()->where('company_id',$this->context->companyId())->where('status','active')
            ->with(['stages'=>fn($q)=>$q->where('status','active')->with(['leads'=>fn($q)=>$q->where('company_id',$this->context->companyId())->with('owner')->latest()])->orderBy('position')])
            ->orderByDesc('is_default')->orderBy('name')->get();
        return view('crm.kanban',compact('pipelines'));
    }
}

