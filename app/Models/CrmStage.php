<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmStage extends Model
{
    protected $fillable = ['pipeline_id','name','position','color'];

    public function pipeline()
    {
        return $this->belongsTo(CrmPipeline::class, 'pipeline_id');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'stage_id');
    }
}
