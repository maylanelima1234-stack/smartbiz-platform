@extends('layouts.smartbiz')

@section('title', 'CRM — Leads')

@push('styles')
    @include('crm.partials.theme')
@endpush

@section('content')
<div class="crm-shell crm-page"><div class="crm-container space-y-4">
<div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between"><div><div class="crm-muted text-xs font-bold uppercase tracking-[.16em]">CRM</div><h1 class="crm-title mt-1">Leads</h1><p class="crm-subtitle">Gerencie oportunidades, prioridades e responsáveis.</p></div><a href="{{ route('crm.leads.create') }}" class="crm-btn crm-btn-primary">+ Novo lead</a></div>
@include('crm.partials.nav')
<form method="GET" class="crm-card grid gap-3 p-4 md:grid-cols-2 xl:grid-cols-[2fr_repeat(6,1fr)_auto]">
<input class="crm-input" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Buscar por nome, e-mail ou telefone">
<select class="crm-select" name="status"><option value="">Todos os status</option>@foreach(['open'=>'Aberto','won'=>'Ganho','lost'=>'Perdido'] as $v=>$l)<option value="{{ $v }}" @selected(($filters['status']??'')===$v)>{{ $l }}</option>@endforeach</select>
<select class="crm-select" name="priority"><option value="">Prioridade</option>@foreach(['low'=>'Baixa','normal'=>'Média','high'=>'Alta'] as $v=>$l)<option value="{{ $v }}" @selected(($filters['priority']??'')===$v)>{{ $l }}</option>@endforeach</select>
<select class="crm-select" name="source"><option value="">Todas as origens</option>@foreach($sources as $source)<option value="{{ $source }}" @selected(($filters['source']??'')===$source)>{{ $source }}</option>@endforeach</select>
<select class="crm-select" name="favorite"><option value="">Todos</option><option value="1" @selected(($filters['favorite']??'')==='1')>Favoritos</option></select>
<select class="crm-select" name="owner"><option value="">Todos os responsáveis</option>@foreach($owners as $owner)<option value="{{ $owner->id }}" @selected((string)($filters['owner']??'')===(string)$owner->id)>{{ $owner->name }}</option>@endforeach</select>
<select class="crm-select" name="mine"><option value="">Carteira completa</option><option value="1" @selected(($filters['mine']??'')==='1')>Somente meus leads</option></select>
<button class="crm-btn crm-btn-primary">Filtrar</button>
</form>
<div class="crm-card overflow-hidden"><div class="crm-table-wrap"><table class="crm-table"><thead><tr><th>Lead</th><th>Etapa</th><th>Responsável</th><th>Origem</th><th>Valor</th><th>Status</th><th></th></tr></thead><tbody>
@forelse($leads as $lead)<tr><td><div class="flex items-center gap-3"><div class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-violet-500/15 text-xs font-black text-violet-300">{{ mb_strtoupper(mb_substr($lead->name,0,1)) }}</div><div><div class="font-bold">{{ $lead->name }} @if($lead->is_favorite)<span class="text-amber-400">★</span>@endif</div><div class="crm-muted text-[11px]">{{ $lead->email ?: ($lead->phone ?: 'Sem contato') }}</div></div></div></td><td>{{ $lead->stage?->name ?? 'Sem etapa' }}</td><td>{{ $lead->owner?->name ?? 'Não atribuído' }}</td><td>{{ $lead->source ?? '—' }}</td><td class="font-bold">R$ {{ number_format((float)$lead->value,2,',','.') }}</td><td><span class="crm-badge"><span class="crm-dot"></span>{{ ucfirst($lead->status) }}</span></td><td><a href="{{ route('crm.leads.show',$lead) }}" class="crm-btn">Ver</a></td></tr>@empty<tr><td colspan="7"><div class="crm-empty">Nenhum lead encontrado.</div></td></tr>@endforelse
</tbody></table></div></div>
<div>{{ $leads->links() }}</div>
</div></div>
@endsection
