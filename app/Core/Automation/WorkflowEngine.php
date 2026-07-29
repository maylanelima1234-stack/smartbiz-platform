<?php

namespace App\Core\Automation;

use App\Domain\CRM\Models\CrmActivity;
use App\Domain\CRM\Models\Lead;
use App\Models\SmartNotification;
use App\Models\SmartWorkflow;
use App\Models\SmartWorkflowRun;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Throwable;

class WorkflowEngine
{
    public function dispatch(string $trigger, Model $subject, array $context = []): void
    {
        $companyId = (int) ($subject->company_id ?? 0);
        if (!$companyId) return;

        SmartWorkflow::query()->where('company_id',$companyId)->where('trigger',$trigger)->where('is_active',true)
            ->each(fn (SmartWorkflow $workflow) => $this->execute($workflow,$subject,$context));
    }

    public function execute(SmartWorkflow $workflow, Model $subject, array $context = []): SmartWorkflowRun
    {
        $run = SmartWorkflowRun::create([
            'workflow_id'=>$workflow->id,'company_id'=>$workflow->company_id,
            'subject_type'=>$subject::class,'subject_id'=>$subject->getKey(),
            'status'=>'running','context'=>$context,'executed_at'=>now(),
        ]);
        try {
            if (!$this->matches($workflow->conditions ?? [], $subject)) {
                $run->update(['status'=>'skipped','result'=>['reason'=>'conditions_not_met']]);
                return $run;
            }
            $results=[];
            foreach ($workflow->actions ?? [] as $action) $results[]=$this->apply($action,$subject);
            $run->update(['status'=>'completed','result'=>$results]);
            $workflow->increment('run_count');
            $workflow->update(['last_run_at'=>now()]);
        } catch (Throwable $e) {
            report($e); $run->update(['status'=>'failed','error'=>$e->getMessage()]);
        }
        return $run->fresh();
    }

    private function matches(array $conditions, Model $subject): bool
    {
        foreach ($conditions as $condition) {
            $field=$condition['field']??null; $operator=$condition['operator']??'equals'; $expected=$condition['value']??null;
            $actual=$field ? data_get($subject,$field) : null;
            $ok=match($operator){'not_equals'=>(string)$actual!==(string)$expected,'contains'=>str_contains(mb_strtolower((string)$actual),mb_strtolower((string)$expected)),'greater_than'=>(float)$actual>(float)$expected,'less_than'=>(float)$actual<(float)$expected,'empty'=>blank($actual),'not_empty'=>filled($actual),default=>(string)$actual===(string)$expected};
            if(!$ok) return false;
        }
        return true;
    }

    private function apply(array $action, Model $subject): array
    {
        $type=$action['type']??'timeline';
        if($type==='notification') {
            SmartNotification::create(['company_id'=>$subject->company_id,'user_id'=>$action['user_id']??($subject->owner_id??null),'title'=>$action['title']??'Automação SmartBiz','message'=>str_replace('{lead}',(string)($subject->name??'registro'),$action['message']??'Uma automação foi executada.'),'type'=>'automation','icon'=>'A','url'=>$subject instanceof Lead ? route('crm.leads.show',$subject) : route('dashboard')]);
        } elseif($type==='set_priority' && $subject instanceof Lead) {
            $subject->update(['priority'=>$action['value']??'high']);
        } elseif($type==='create_follow_up' && $subject instanceof Lead) {
            CrmActivity::create(['public_id'=>(string)Str::ulid(),'company_id'=>$subject->company_id,'lead_id'=>$subject->id,'user_id'=>$action['user_id']??$subject->owner_id,'type'=>'follow_up','title'=>$action['title']??'Follow-up automático','description'=>$action['description']??'Criado pelo SmartBiz Automation.','due_at'=>now()->addDays((int)($action['days']??1)),'metadata'=>['workflow'=>true]]);
        }
        return ['type'=>$type,'ok'=>true];
    }
}
