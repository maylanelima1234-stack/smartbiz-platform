<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SmartWorkflowRun extends Model
{
    protected $fillable = ['workflow_id','company_id','subject_type','subject_id','status','context','result','error','executed_at'];
    protected function casts(): array { return ['context'=>'array','result'=>'array','executed_at'=>'datetime']; }
    public function workflow(): BelongsTo { return $this->belongsTo(SmartWorkflow::class, 'workflow_id'); }
    public function subject(): MorphTo { return $this->morphTo(); }
}
