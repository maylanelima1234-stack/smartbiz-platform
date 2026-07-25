@csrf
<div class="grid gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="text-sm font-medium text-slate-700">Nome *</label>
        <input name="name" value="{{ old('name', $lead->name ?? '') }}" required class="mt-1 w-full rounded-xl border-slate-300">
        @error('name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
    </div>
    <div><label class="text-sm font-medium text-slate-700">E-mail</label><input type="email" name="email" value="{{ old('email', $lead->email ?? '') }}" class="mt-1 w-full rounded-xl border-slate-300"></div>
    <div><label class="text-sm font-medium text-slate-700">Telefone</label><input name="phone" value="{{ old('phone', $lead->phone ?? '') }}" class="mt-1 w-full rounded-xl border-slate-300"></div>
    <div><label class="text-sm font-medium text-slate-700">Pipeline</label><select name="pipeline_id" class="mt-1 w-full rounded-xl border-slate-300"><option value="">Selecione</option>@foreach($pipelines as $pipeline)<option value="{{ $pipeline->id }}" @selected(old('pipeline_id', $lead->pipeline_id ?? '') == $pipeline->id)>{{ $pipeline->name }}</option>@endforeach</select></div>
    <div><label class="text-sm font-medium text-slate-700">Etapa</label><select name="stage_id" class="mt-1 w-full rounded-xl border-slate-300"><option value="">Selecione</option>@foreach($stages as $stage)<option value="{{ $stage->id }}" @selected(old('stage_id', $lead->stage_id ?? '') == $stage->id)>{{ $stage->name }}</option>@endforeach</select></div>
    <div><label class="text-sm font-medium text-slate-700">Responsável</label><select name="owner_id" class="mt-1 w-full rounded-xl border-slate-300"><option value="">Não atribuído</option>@foreach($users as $user)<option value="{{ $user->id }}" @selected(old('owner_id', $lead->owner_id ?? '') == $user->id)>{{ $user->name }}</option>@endforeach</select></div>
    <div><label class="text-sm font-medium text-slate-700">Origem</label><input name="source" value="{{ old('source', $lead->source ?? 'Manual') }}" class="mt-1 w-full rounded-xl border-slate-300"></div>
    <div><label class="text-sm font-medium text-slate-700">Campanha</label><input name="campaign" value="{{ old('campaign', $lead->campaign ?? '') }}" class="mt-1 w-full rounded-xl border-slate-300"></div>
    <div><label class="text-sm font-medium text-slate-700">Valor</label><input type="number" min="0" step="0.01" name="value" value="{{ old('value', $lead->value ?? 0) }}" class="mt-1 w-full rounded-xl border-slate-300"></div>
    <div><label class="text-sm font-medium text-slate-700">Status</label><select name="status" class="mt-1 w-full rounded-xl border-slate-300">@foreach(['open'=>'Aberto','won'=>'Ganho','lost'=>'Perdido'] as $value=>$label)<option value="{{ $value }}" @selected(old('status', $lead->status ?? 'open') === $value)>{{ $label }}</option>@endforeach</select></div>
    <div><label class="text-sm font-medium text-slate-700">Prioridade</label><select name="priority" class="mt-1 w-full rounded-xl border-slate-300">@foreach(['low'=>'Baixa','normal'=>'Normal','high'=>'Alta','urgent'=>'Urgente'] as $value=>$label)<option value="{{ $value }}" @selected(old('priority', $lead->priority ?? 'normal') === $value)>{{ $label }}</option>@endforeach</select></div>
    <div><label class="text-sm font-medium text-slate-700">Score</label><input type="number" min="0" max="100" name="score" value="{{ old('score', $lead->score ?? 0) }}" class="mt-1 w-full rounded-xl border-slate-300"></div>
    <div><label class="text-sm font-medium text-slate-700">Próximo follow-up</label><input type="datetime-local" name="next_follow_up_at" value="{{ old('next_follow_up_at', isset($lead) && $lead->next_follow_up_at ? $lead->next_follow_up_at->format('Y-m-d\\TH:i') : '') }}" class="mt-1 w-full rounded-xl border-slate-300"></div>
    <div class="sm:col-span-2"><label class="text-sm font-medium text-slate-700">Observações</label><textarea name="notes" rows="5" class="mt-1 w-full rounded-xl border-slate-300">{{ old('notes', $lead->notes ?? '') }}</textarea></div>
</div>
<div class="mt-6 flex justify-end gap-3">
    <a href="{{ route('leads.index') }}" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700">Cancelar</a>
    <button class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white">Salvar lead</button>
</div>
