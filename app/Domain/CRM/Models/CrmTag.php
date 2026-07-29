<?php

namespace App\Domain\CRM\Models;

use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrmTag extends Model
{
    use SoftDeletes;

    protected $table = 'crm_tags';

    protected $fillable = ['public_id', 'company_id', 'name', 'slug', 'color'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function leads(): BelongsToMany
    {
        return $this->belongsToMany(Lead::class, 'crm_lead_tag', 'tag_id', 'lead_id')
            ->withTimestamps();
    }
}
