@extends('layouts.smartbiz')
@section('title','Auditoria Enterprise')
@section('content')
@php
    $pageLogs = collect($logs->items());
    $userCount = $pageLogs->pluck('user_id')->filter()->unique()->count();
    $systemCount = $pageLogs->whereNull('user_id')->count();
@endphp
<div class="sb-page-head mb-4">
    <div>
        <span class="sb-page-kicker">SEGURANÇA E GOVERNANÇA</span>
        <h1 class="sb-page-title mb-1">Auditoria Enterprise</h1>
        <p class="sb-page-subtitle">Acompanhe alterações, responsáveis e registros com rastreabilidade completa.</p>
    </div>
    <div class="sb-audit-security"><span>✓</span><div><strong>Auditoria ativa</strong><small>Registros protegidos por empresa</small></div></div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="sb-card sb-compact-metric"><div class="sb-compact-icon sb-icon-purple">≡</div><div><span>Eventos encontrados</span><strong>{{ number_format($logs->total(),0,',','.') }}</strong><small>Considerando o filtro atual</small></div></div></div>
    <div class="col-md-4"><div class="sb-card sb-compact-metric"><div class="sb-compact-icon sb-icon-blue">◉</div><div><span>Usuários nesta página</span><strong>{{ $userCount }}</strong><small>Responsáveis identificados</small></div></div></div>
    <div class="col-md-4"><div class="sb-card sb-compact-metric"><div class="sb-compact-icon sb-icon-slate">⚙</div><div><span>Eventos do sistema</span><strong>{{ $systemCount }}</strong><small>Processos automáticos</small></div></div></div>
</div>

<div class="sb-card mb-4">
    <div class="sb-card-body">
        <form class="sb-filter-bar" method="GET">
            <div class="sb-filter-field">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                <input name="event" value="{{ request('event') }}" placeholder="Pesquisar por evento, ex.: crm.lead.updated">
            </div>
            <button class="sb-btn sb-btn-primary">Aplicar filtro</button>
            @if(request('event'))<a href="{{ route('audit.index') }}" class="sb-btn sb-btn-secondary">Limpar</a>@endif
        </form>
    </div>
</div>

<div class="sb-card">
    <div class="sb-card-header sb-list-heading">
        <div><h2 class="sb-card-title">Histórico de atividades</h2><div class="small sb-muted mt-1">Eventos ordenados do mais recente para o mais antigo.</div></div>
        <span class="sb-badge sb-badge-primary">Página {{ $logs->currentPage() }} de {{ $logs->lastPage() }}</span>
    </div>
    <div class="table-responsive">
        <table class="table sb-audit-table mb-0 align-middle">
            <thead><tr><th>Data e hora</th><th>Evento</th><th>Responsável</th><th>Registro afetado</th><th>Endereço IP</th></tr></thead>
            <tbody>
            @forelse($logs as $log)
                @php
                    $eventParts = explode('.', $log->event);
                    $eventAction = last($eventParts);
                    $eventTone = str_contains($eventAction,'created') ? 'success' : (str_contains($eventAction,'deleted') ? 'danger' : (str_contains($eventAction,'completed') ? 'primary' : 'neutral'));
                @endphp
                <tr>
                    <td><div class="sb-date-cell"><strong>{{ $log->occurred_at?->format('d/m/Y') }}</strong><span>{{ $log->occurred_at?->format('H:i:s') }}</span></div></td>
                    <td><span class="sb-event-pill sb-event-{{ $eventTone }}">{{ $log->event }}</span></td>
                    <td><div class="sb-user-cell"><span class="sb-mini-avatar">{{ strtoupper(substr($log->user?->name ?: 'S',0,1)) }}</span><div><strong>{{ $log->user?->name ?: 'Sistema' }}</strong><small>{{ $log->user ? 'Usuário autenticado' : 'Processo automático' }}</small></div></div></td>
                    <td><div class="sb-record-cell"><strong>{{ class_basename($log->auditable_type) }}</strong><span>#{{ $log->auditable_id }}</span></div></td>
                    <td><code class="sb-ip-code">{{ $log->ip_address ?: '—' }}</code></td>
                </tr>
            @empty
                <tr><td colspan="5"><div class="sb-empty-professional"><div class="sb-empty-icon">≡</div><h3>Nenhum registro encontrado</h3><p>Altere o filtro ou aguarde novas atividades no sistema.</p></div></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())<div class="sb-pagination-wrap">{{ $logs->links() }}</div>@endif
</div>
@endsection
