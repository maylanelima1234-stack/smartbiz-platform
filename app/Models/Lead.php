<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'company_id','pipeline_id','stage_id','assigned_user_id',
        'name','email','phone','source','campaign','value','status','priority','notes','next_follow_up_at'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'next_follow_up_at' => 'datetime',
    ];

    public function company(){ return $this->belongsTo(Company::class); }
    public function pipeline(){ return $this->belongsTo(CrmPipeline::class, 'pipeline_id'); }
    public function stage(){ return $this->belongsTo(CrmStage::class, 'stage_id'); }
    public function assignedUser(){ return $this->belongsTo(User::class, 'assigned_user_id'); }
}
