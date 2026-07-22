<?php
namespace App\Domain\CRM\Services;
use App\Core\Audit\Facades\SmartAudit;
use App\Core\Context\PlatformContext;
use App\Domain\CRM\Models\CrmActivity;
use App\Domain\CRM\Models\Lead;
use Illuminate\Support\Str;
class ActivityService {
 public function __construct(private readonly PlatformContext $context) {}
 public function create(Lead $lead,array $data):CrmActivity {
  abort_unless((int)$lead->company_id===(int)$this->context->companyId(),404);
  $activity=CrmActivity::query()->create([
   'public_id'=>(string)Str::ulid(),'company_id'=>$this->context->companyId(),'lead_id'=>$lead->getKey(),
   'user_id'=>auth()->id(),'type'=>$data['type'],'title'=>$data['title'],'description'=>$data['description']??null,
   'due_at'=>$data['due_at']??null,'metadata'=>$data['metadata']??null,
  ]);
  SmartAudit::log('crm.activity.created',$activity,[],$activity->getAttributes(),['lead_id'=>$lead->getKey()]);
  return $activity->load('user');
 }
 public function complete(CrmActivity $activity):CrmActivity {
  abort_unless((int)$activity->company_id===(int)$this->context->companyId(),404);
  $activity->update(['completed_at'=>now()]);
  SmartAudit::log('crm.activity.completed',$activity,[],['completed_at'=>$activity->completed_at]);
  return $activity->fresh('user');
 }
}
