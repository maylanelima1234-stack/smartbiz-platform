<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmPipeline extends Model
{
    protected $fillable = ['name','description','status'];

    public function stages()
    {
        return $this->hasMany(CrmStage::class, 'pipeline_id')->orderBy('position');
    }
}
