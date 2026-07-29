@extends('layouts.smartbiz')
@section('title', 'Dashboard Executivo — SmartBiz')
@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div><div class="text-uppercase small fw-bold text-primary">BUILD 0.9</div><h1 class="h2 fw-bold mb-1">Dashboard Executivo</h1><p class="text-muted mb-0">Visão estratégica de {{ $activeCompany?->trade_name ?: $activeCompany?->name }}.</p></div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-3">Centro de Operações</a>
    </div>
    <div class="row g-3 mb-4">
        @foreach([
            ['Pipeline aberto', $metrics['pipeline'], 'money'], ['Receita ganha', $metrics['won_value'], 'money'], ['Ticket médio', $metrics['ticket'], 'money'],
            ['Conversão', $metrics['conversion'].'%', 'text'], ['Leads no mês', $metrics['month_leads'], 'text'], ['Crescimento mensal', ($metrics['growth']>=0?'+':'').$metrics['growth'].'%', 'text'],
            ['Atividades vencidas', $metrics['overdue'], 'text'], ['Automações ativas', $metrics['automations'], 'text']
        ] as [$label,$value,$type])
        <div class="col-12 col-sm-6 col-xl-3"><div class="card border-0 shadow-sm rounded-4 h-100"><div class="card-body"><div class="small text-muted fw-semibold">{{ $label }}</div><div class="h3 fw-bold mt-2 mb-0">{{ $type==='money' ? 'R$ '.number_format((float)$value,2,',','.') : $value }}</div></div></div></div>
        @endforeach
    </div>
    @if($metrics['automation_failures']>0)<div class="alert alert-danger rounded-4 border-0">Existem {{ $metrics['automation_failures'] }} execução(ões) de automação com falha nos últimos 30 dias. <a href="{{ route('automations.runs') }}" class="alert-link">Ver histórico</a>.</div>@endif
    <div class="row g-4">
        <div class="col-12 col-xl-6"><div class="card border-0 shadow-sm rounded-4"><div class="card-body"><h2 class="h5 fw-bold">Conversão por origem</h2><div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Origem</th><th>Leads</th><th>Ganhos</th><th>Conversão</th></tr></thead><tbody>@forelse($bySource as $row)<tr><td>{{ ucfirst($row->source ?: 'Não informada') }}</td><td>{{ $row->total }}</td><td>{{ $row->won }}</td><td>{{ $row->total ? number_format(($row->won/$row->total)*100,1,',','.') : '0,0' }}%</td></tr>@empty<tr><td colspan="4" class="text-muted">Sem dados.</td></tr>@endforelse</tbody></table></div></div></div></div>
        <div class="col-12 col-xl-6"><div class="card border-0 shadow-sm rounded-4"><div class="card-body"><h2 class="h5 fw-bold">Desempenho por responsável</h2><div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Responsável</th><th>Leads</th><th>Ganhos</th><th>Pipeline</th></tr></thead><tbody>@forelse($byOwner as $row)<tr><td>{{ $row->owner?->name ?: 'Não atribuído' }}</td><td>{{ $row->total }}</td><td>{{ $row->won }}</td><td>R$ {{ number_format((float)$row->pipeline_value,2,',','.') }}</td></tr>@empty<tr><td colspan="4" class="text-muted">Sem dados.</td></tr>@endforelse</tbody></table></div></div></div></div>
    </div>
</div>
@endsection
