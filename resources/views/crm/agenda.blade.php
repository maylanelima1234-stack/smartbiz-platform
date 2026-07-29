@extends('layouts.smartbiz')

@section('title', 'CRM — Agenda comercial')

@push('styles')
    @include('crm.partials.theme')
@endpush

@section('content')
<div class="crm-shell crm-page"><div class="crm-container space-y-4">
<div><div class="crm-muted text-xs font-bold uppercase tracking-[.16em]">CRM</div><h1 class="crm-title mt-1">Agenda comercial</h1><p class="crm-subtitle">Tarefas, reuniões e follow-ups em um só lugar.</p></div>
@include('crm.partials.nav')
<div class="flex flex-wrap gap-2">@foreach(['upcoming'=>'Próximas','today'=>'Hoje','overdue'=>'Vencidas','all'=>'Todas'] as $key=>$label)<a href="{{ route('crm.agenda',['period'=>$key]) }}" class="crm-btn {{ $period===$key ? 'crm-btn-primary' : '' }}">{{ $label }}</a>@endforeach</div>
<div class="crm-card overflow-hidden">@forelse($activities as $activity)<div class="flex flex-col gap-3 border-b border-[var(--crm-border)] p-4 last:border-0 md:flex-row md:items-center md:justify-between"><div class="flex items-start gap-3"><div class="crm-icon">◷</div><div><div class="flex flex-wrap items-center gap-2"><span class="crm-badge">{{ ucfirst($activity->type) }}</span><h3 class="text-sm font-bold">{{ $activity->title }}</h3></div><p class="crm-muted mt-1 text-xs"><a href="{{ $activity->lead ? route('crm.leads.show',$activity->lead) : '#' }}">{{ $activity->lead?->name ?? 'Lead removido' }}</a> · {{ $activity->user?->name ?? 'Sistema' }}</p></div></div><div class="flex items-center gap-3"><span class="text-xs font-bold {{ $activity->due_at?->isPast() ? 'text-red-400' : 'crm-muted' }}">{{ $activity->due_at?->timezone('America/Sao_Paulo')->format('d/m/Y H:i') ?? 'Sem prazo' }}</span><form method="POST" action="{{ route('crm.activities.complete',$activity) }}">@csrf @method('PATCH')<button class="crm-btn crm-btn-primary">Concluir</button></form></div></div>@empty<div class="crm-empty">Nenhuma atividade encontrada.</div>@endforelse</div>
<div>{{ $activities->links() }}</div>
</div></div>
@endsection
