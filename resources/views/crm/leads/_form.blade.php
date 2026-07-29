@include('crm.partials.theme')
@php($editing = isset($lead))

@if ($errors->any())
    <div class="mb-5 rounded-2xl border border-red-300 bg-red-50 p-4 text-red-900">
        <div class="font-bold">Não foi possível salvar o lead.</div>
        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="crm-shell grid gap-4 lg:grid-cols-2">
<div><label class="crm-label">Nome *</label><input class="crm-input" name="name" value="{{ old('name',$lead->name ?? '') }}" required>@error('name')<div class="mt-1 text-xs text-red-600">{{ $message }}</div>@enderror</div>
<div><label class="crm-label">E-mail</label><input class="crm-input" type="email" name="email" value="{{ old('email',$lead->email ?? '') }}">@error('email')<div class="mt-1 text-xs text-red-600">{{ $message }}</div>@enderror</div>
<div><label class="crm-label">Telefone / WhatsApp</label><input class="crm-input" name="phone" value="{{ old('phone',$lead->phone ?? '') }}"></div>
<div><label class="crm-label">Origem *</label><select class="crm-select" name="source" required><option value="">Selecione</option>@foreach($sourceOptions as $option)<option value="{{ $option['value'] }}" @selected(old('source',$lead->source ?? '')===$option['value'])>{{ $option['label'] }}</option>@endforeach</select>@error('source')<div class="mt-1 text-xs text-red-600">{{ $message }}</div>@enderror</div>
<div><label class="crm-label">Campanha</label><input class="crm-input" name="campaign" value="{{ old('campaign',$lead->campaign ?? '') }}"></div>
<div><label class="crm-label">Valor estimado</label><input class="crm-input" inputmode="decimal" name="value" value="{{ old('value',$lead->value ?? '0,00') }}" placeholder="0,00">@error('value')<div class="mt-1 text-xs text-red-600">{{ $message }}</div>@enderror</div>
<div><label class="crm-label">Pipeline</label><select class="crm-select" name="pipeline_id"><option value="">Selecione</option>@foreach($pipelines as $pipeline)<option value="{{ $pipeline->id }}" @selected(old('pipeline_id',$lead->pipeline_id ?? '')==$pipeline->id)>{{ $pipeline->name }}</option>@endforeach</select></div>
<div><label class="crm-label">Etapa</label><select class="crm-select" name="stage_id"><option value="">Selecione</option>@foreach($stages as $stage)<option value="{{ $stage->id }}" data-pipeline="{{ $stage->pipeline_id }}" @selected(old('stage_id',$lead->stage_id ?? '')==$stage->id)>{{ $stage->name }}</option>@endforeach</select></div>
<div><label class="crm-label">Responsável</label><select class="crm-select" name="owner_id"><option value="">Não atribuído</option>@foreach($users as $user)<option value="{{ $user->id }}" @selected(old('owner_id',$lead->owner_id ?? '')==$user->id)>{{ $user->name }}</option>@endforeach</select></div>
<div><label class="crm-label">Prioridade</label><select class="crm-select" name="priority">@foreach($priorityOptions as $option)<option value="{{ $option['value'] }}" @selected(old('priority',$lead->priority ?? 'normal')===$option['value'])>{{ $option['label'] }}</option>@endforeach</select></div>
<div><label class="crm-label">Status</label><select class="crm-select" name="status">@foreach($statusOptions as $option)<option value="{{ $option['value'] }}" @selected(old('status',$lead->status ?? 'open')===$option['value'])>{{ $option['label'] }}</option>@endforeach</select></div>
<div><label class="crm-label">Score</label><input class="crm-input" type="number" min="0" max="100" name="score" value="{{ old('score',$lead->score ?? 0) }}"></div>
<div class="lg:col-span-2"><label class="crm-label">Observações</label><textarea class="crm-textarea" name="notes">{{ old('notes',$lead->notes ?? '') }}</textarea></div>
</div>

@push('scripts')
<script>
(() => {
    const pipeline = document.querySelector('[name="pipeline_id"]');
    const stage = document.querySelector('[name="stage_id"]');
    if (!pipeline || !stage) return;
    const syncStages = () => {
        const selectedPipeline = pipeline.value;
        [...stage.options].forEach(option => {
            if (!option.value) return;
            option.hidden = !!selectedPipeline && option.dataset.pipeline !== selectedPipeline;
            option.disabled = option.hidden;
        });
        if (stage.selectedOptions[0]?.disabled) stage.value = '';
    };
    pipeline.addEventListener('change', syncStages);
    syncStages();
})();
</script>
@endpush
