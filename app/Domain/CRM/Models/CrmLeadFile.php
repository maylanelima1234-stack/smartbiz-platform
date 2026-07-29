<?php

namespace App\Domain\CRM\Models;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrmLeadFile extends Model
{
    use SoftDeletes;

    protected $table = 'crm_lead_files';

    protected $fillable = [
        'public_id', 'company_id', 'lead_id', 'uploaded_by', 'disk',
        'path', 'original_name', 'mime_type', 'size',
    ];

    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
    public function uploader(): BelongsTo { return $this->belongsTo(User::class, 'uploaded_by'); }
}
