<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SmartWorkflow extends Model
{
    protected $fillable = ['company_id','created_by','name','description','trigger','conditions','actions','is_active','run_count','last_run_at'];
    protected function casts(): array { return ['conditions'=>'array','actions'=>'array','is_active'=>'boolean','last_run_at'=>'datetime']; }
    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function runs(): HasMany { return $this->hasMany(SmartWorkflowRun::class, 'workflow_id')->latest('executed_at'); }
}
