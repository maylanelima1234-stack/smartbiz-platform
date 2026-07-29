<?php

namespace App\Domain\CRM\Http\Requests;

use App\Core\Support\Money;
use App\Domain\CRM\Enums\LeadPriority;
use App\Domain\CRM\Enums\LeadSource;
use App\Domain\CRM\Enums\LeadStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class StoreLeadRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $data = [
            'source' => LeadSource::normalize($this->input('source')),
            'priority' => LeadPriority::normalize($this->input('priority')),
            'status' => LeadStatus::normalize($this->input('status')),
        ];

        if ($this->has('value')) {
            try {
                $data['value'] = Money::parseBrazilian((string) $this->input('value'))->decimal();
            } catch (InvalidArgumentException) {
                $data['value'] = $this->input('value');
            }
        }

        foreach (['email', 'phone', 'campaign', 'notes'] as $field) {
            if ($this->has($field) && is_string($this->input($field))) {
                $data[$field] = trim($this->input($field));
            }
        }

        $this->merge($data);
    }

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
            'source' => ['required', Rule::enum(LeadSource::class)],
            'campaign' => ['nullable', 'string', 'max:160'],
            'status' => ['required', Rule::enum(LeadStatus::class)],
            'priority' => ['required', Rule::enum(LeadPriority::class)],
            'score' => ['nullable', 'integer', 'between:0,100'],
            'value' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'next_follow_up_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Informe o nome do lead.',
            'email.email' => 'Informe um e-mail válido.',
            'source.required' => 'Selecione a origem do lead.',
            'source.enum' => 'A origem informada não é válida.',
            'priority.enum' => 'A prioridade informada não é válida.',
            'status.enum' => 'O status informado não é válido.',
            'pipeline_id.exists' => 'O pipeline selecionado não existe.',
            'stage_id.exists' => 'A etapa selecionada não existe.',
            'owner_id.exists' => 'O responsável selecionado não existe.',
            'value.numeric' => 'Informe um valor válido.',
        ];
    }
}
