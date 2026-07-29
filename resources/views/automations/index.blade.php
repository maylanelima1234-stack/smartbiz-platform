@extends('layouts.smartbiz')
@section('title','SmartBiz Automation')
@section('content')
@php
    $activeCount = $workflows->where('is_active', true)->count();
    $pausedCount = $workflows->where('is_active', false)->count();
    $runsCount = $workflows->sum('runs_count');
@endphp
<div class="sb-page-head mb-4">
    <div>
        <span class="sb-page-kicker">AUTOMAÇÕES</span>
        <h1 class="sb-page-title mb-1">SmartBiz Automation</h1>
        <p class="sb-page-subtitle">Crie fluxos inteligentes para reduzir tarefas manuais e acelerar o atendimento comercial.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('automations.runs') }}" class="sb-btn sb-btn-secondary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 4v5h5"/><path d="M12 7v5l3 2"/></svg>
            Histórico
        </a>
        <a href="{{ route('automations.create') }}" class="sb-btn sb-btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            Nova automação
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="sb-card sb-compact-metric"><div class="sb-compact-icon sb-icon-purple">⚡</div><div><span>Automações ativas</span><strong>{{ $activeCount }}</strong><small>Executando regras automaticamente</small></div></div></div>
    <div class="col-md-4"><div class="sb-card sb-compact-metric"><div class="sb-compact-icon sb-icon-slate">Ⅱ</div><div><span>Automações pausadas</span><strong>{{ $pausedCount }}</strong><small>Podem ser reativadas a qualquer momento</small></div></div></div>
    <div class="col-md-4"><div class="sb-card sb-compact-metric"><div class="sb-compact-icon sb-icon-green">✓</div><div><span>Execuções registradas</span><strong>{{ $runsCount }}</strong><small>Histórico total das regras</small></div></div></div>
</div>

@if(session('success'))<div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">{{ session('error') }}</div>@endif

<div class="sb-card">
    <div class="sb-card-header sb-list-heading">
        <div><h2 class="sb-card-title">Fluxos configurados</h2><div class="small sb-muted mt-1">Gerencie, teste e acompanhe suas automações.</div></div>
        <span class="sb-badge sb-badge-neutral">{{ $workflows->count() }} {{ $workflows->count() === 1 ? 'automação' : 'automações' }}</span>
    </div>
    <div class="sb-workflow-list">
        @forelse($workflows as $workflow)
            @php
                $triggerLabels = ['lead.created'=>'Lead criado','lead.updated'=>'Lead atualizado','lead.moved'=>'Lead movido'];
                $triggerLabel = $triggerLabels[$workflow->trigger] ?? $workflow->trigger;
            @endphp
            <article class="sb-workflow-row">
                <div class="sb-workflow-main">
                    <div class="sb-workflow-symbol">⚡</div>
                    <div class="min-w-0">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <h3 class="sb-workflow-title">{{ $workflow->name }}</h3>
                            <span class="sb-badge {{ $workflow->is_active ? 'sb-badge-success' : 'sb-badge-neutral' }}">
                                <span class="sb-status-dot"></span>{{ $workflow->is_active ? 'Ativa' : 'Pausada' }}
                            </span>
                        </div>
                        <p class="sb-workflow-description">{{ $workflow->description ?: 'Automação sem descrição cadastrada.' }}</p>
                        <div class="sb-workflow-meta">
                            <span><b>Gatilho</b> {{ $triggerLabel }}</span>
                            <span><b>Execuções</b> {{ $workflow->runs_count }}</span>
                            <span><b>Última execução</b> {{ $workflow->last_run_at?->diffForHumans() ?: 'Nunca' }}</span>
                        </div>
                    </div>
                </div>
                <div class="sb-workflow-actions">
                    <form method="POST" action="{{ route('automations.test',$workflow) }}">@csrf<button class="sb-action-btn" title="Testar automação"><span>▶</span> Testar</button></form>
                    <form method="POST" action="{{ route('automations.toggle',$workflow) }}">@csrf @method('PATCH')<button class="sb-action-btn" title="{{ $workflow->is_active?'Pausar':'Ativar' }} automação"><span>{{ $workflow->is_active?'Ⅱ':'▶' }}</span> {{ $workflow->is_active?'Pausar':'Ativar' }}</button></form>
                    <form method="POST" action="{{ route('automations.destroy',$workflow) }}" onsubmit="return confirm('Excluir esta automação?')">@csrf @method('DELETE')<button class="sb-action-btn sb-action-danger" title="Excluir automação"><span>⌫</span> Excluir</button></form>
                </div>
            </article>
        @empty
            <div class="sb-empty-professional"><div class="sb-empty-icon">⚡</div><h3>Nenhuma automação criada</h3><p>Crie sua primeira regra para automatizar tarefas repetitivas do CRM.</p><a href="{{ route('automations.create') }}" class="sb-btn sb-btn-primary">Criar primeira automação</a></div>
        @endforelse
    </div>
</div>
@endsection
