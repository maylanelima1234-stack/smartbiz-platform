<?php

namespace App\Domain\CRM\Models;

use App\Core\Audit\Traits\AuditsModelChanges;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use AuditsModelChanges, SoftDeletes;

    protected $table = 'leads';

    protected $fillable = [
        'public_id', 'company_id', 'pipeline_id', 'stage_id', 'owner_id',
        'name', 'email', 'phone', 'source', 'campaign', 'status', 'priority',
        'score', 'is_favorite', 'value', 'notes', 'last_contact_at',
        'next_follow_up_at', 'won_at', 'lost_at',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'is_favorite' => 'boolean',
            'value' => 'decimal:2',
            'last_contact_at' => 'datetime',
            'next_follow_up_at' => 'datetime',
            'won_at' => 'datetime',
            'lost_at' => 'datetime',
        ];
    }

    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function pipeline(): BelongsTo { return $this->belongsTo(CrmPipeline::class, 'pipeline_id'); }
    public function stage(): BelongsTo { return $this->belongsTo(CrmStage::class, 'stage_id'); }
    public function owner(): BelongsTo { return $this->belongsTo(User::class, 'owner_id'); }
    public function activities(): HasMany { return $this->hasMany(CrmActivity::class)->latest(); }
    public function comments(): HasMany { return $this->hasMany(CrmComment::class)->latest(); }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(CrmTag::class, 'crm_lead_tag', 'lead_id', 'tag_id')
            ->withTimestamps();
    }
}
