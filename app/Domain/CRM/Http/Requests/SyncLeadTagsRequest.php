<?php

namespace App\Domain\CRM\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncLeadTagsRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:crm_tags,id'],
        ];
    }
}
