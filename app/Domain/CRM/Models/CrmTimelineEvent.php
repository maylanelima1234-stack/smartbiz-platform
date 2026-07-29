<?php

namespace App\Domain\CRM\Models;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmTimelineEvent extends Model
{
    protected $table = 'crm_timeline_events';

    protected $fillable = [
        'public_id', 'company_id', 'lead_id', 'user_id',
        'type', 'title', 'description', 'metadata',
    ];

    protected function casts(): array
    {
        return ['metadata' => 'array'];
    }

    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
