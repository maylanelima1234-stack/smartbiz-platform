<?php

namespace App\Domain\CRM\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pipeline_id' => ['nullable', 'integer', 'exists:crm_pipelines,id'],
            'stage_id' => ['nullable', 'integer', 'exists:crm_stages,id'],
            'owner_id' => ['nullable', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'max:160'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'source' => ['nullable', 'string', 'max:80'],
            'campaign' => ['nullable', 'string', 'max:160'],
            'status' => ['nullable', 'in:open,won,lost'],
            'priority' => ['nullable', 'in:low,normal,high,urgent'],
            'score' => ['nullable', 'integer', 'between:0,100'],
            'value' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'next_follow_up_at' => ['nullable', 'date'],
        ];
    }
}
