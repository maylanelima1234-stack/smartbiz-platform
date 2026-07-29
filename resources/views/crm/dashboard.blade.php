@extends('layouts.smartbiz')

@section('title', 'CRM — Central comercial')

@push('styles')
    @include('crm.partials.theme')
@endpush

@section('content')
    @php
        $closed = $won + $lost;
        $openRate = $total > 0 ? round(($open / $total) * 100, 1) : 0;
        $wonRate = $closed > 0 ? round(($won / $closed) * 100, 1) : 0;
        $lostRate = $closed > 0 ? round(($lost / $closed) * 100, 1) : 0;
    @endphp
    <div class="crm-shell crm-page">
        <div class="crm-container space-y-4">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                <div><div class="crm-muted text-xs font-bold uppercase tracking-[.16em]">Smart CRM</div><h1 class="crm-title mt-1">Central comercial</h1><p class="crm-subtitle">Funil, receita e atividades em uma visão executiva.</p></div>
                <div class="flex flex-wrap gap-2"><a href="{{ route('crm.leads.index') }}" class="crm-btn">Ver leads</a><a href="{{ route('crm.leads.create') }}" class="crm-btn crm-btn-primary">+ Novo lead</a></div>
            </div>
            @include('crm.partials.nav')
<div class="grid gap-3 md:grid-cols-3">
    <div class="crm-card p-4"><div class="crm-muted text-xs font-bold uppercase">Leads hoje</div><div class="mt-2 text-3xl font-black">{{ $today }}</div></div>
    <div class="crm-card p-4"><div class="crm-muted text-xs font-bold uppercase">Nesta semana</div><div class="mt-2 text-3xl font-black">{{ $week }}</div></div>
    <div class="crm-card p-4"><div class="crm-muted text-xs font-bold uppercase">Neste mês</div><div class="mt-2 text-3xl font-black">{{ $month }}</div></div>
</div>

            <section class="crm-grid-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                @foreach([
                    ['Leads no funil',number_format($total,0,',','.'),'Base comercial completa','◎'],
                    ['Em andamento',number_format($open,0,',','.'),number_format($openRate,1,',','.').'% do funil','◷'],
                    ['Receita ganha','R$ '.number_format($won_value,2,',','.'),'Ticket médio: R$ '.number_format($average_ticket ?? 0,2,',','.'),'↗'],
                    ['Conversão',number_format($conversion_rate,1,',','.').'%',$won.' oportunidades ganhas','✓'],
                ] as $item)
                    <div class="crm-card crm-metric"><div class="flex items-start justify-between"><div><div class="crm-metric-label">{{ $item[0] }}</div><div class="crm-metric-value">{{ $item[1] }}</div><div class="crm-metric-note">{{ $item[2] }}</div></div><div class="crm-icon">{{ $item[3] }}</div></div></div>
                @endforeach
            </section>

            <section class="grid gap-4 xl:grid-cols-[1.25fr_.75fr]">
                <div class="crm-card overflow-hidden">
                    <div class="crm-section-head"><div><h2 class="crm-section-title">Centro de atenção</h2><p class="crm-section-subtitle">O que precisa de ação agora</p></div><span class="crm-badge">{{ $attention_items->count() }}</span></div>
                    <div>
                        @forelse($attention_items as $item)
                            <a href="{{ $item['url'] }}" class="flex items-center gap-3 border-b border-[var(--crm-border)] px-4 py-3 last:border-0 hover:bg-[var(--crm-card-2)]">
                                <div class="grid h-9 w-9 shrink-0 place-items-center rounded-xl {{ $item['type']==='overdue' ? 'bg-red-500/15 text-red-300' : 'bg-amber-500/15 text-amber-300' }}">{{ $item['type']==='overdue' ? '!' : '◷' }}</div>
                                <div class="min-w-0"><div class="truncate text-sm font-bold">{{ $item['title'] }}</div><div class="crm-muted truncate text-[11px]">{{ $item['detail'] }}</div></div>
                            </a>
                        @empty<div class="crm-empty">Nenhuma ação crítica no momento.</div>@endforelse
                    </div>
                </div>
                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-1">
                    <div class="crm-card p-4"><div class="crm-muted text-xs font-bold uppercase">Vencem hoje</div><div class="mt-2 text-3xl font-black">{{ $due_today_count }}</div><a href="{{ route('crm.agenda',['period'=>'today']) }}" class="mt-3 inline-block text-xs font-bold text-violet-400">Abrir agenda</a></div>
                    <div class="crm-card p-4"><div class="crm-muted text-xs font-bold uppercase">Leads parados +3 dias</div><div class="mt-2 text-3xl font-black">{{ $stalled_count }}</div><a href="{{ route('crm.kanban') }}" class="mt-3 inline-block text-xs font-bold text-violet-400">Revisar pipeline</a></div>
                </div>
            </section>

            <section class="grid gap-4 xl:grid-cols-[1.45fr_.75fr]">
                <div class="crm-card">
                    <div class="crm-section-head"><div><h2 class="crm-section-title">Saúde do funil</h2><p class="crm-section-subtitle">Distribuição das oportunidades atuais</p></div><a href="{{ route('crm.kanban') }}" class="crm-btn">Abrir pipeline</a></div>
                    <div class="crm-body grid gap-5 md:grid-cols-3">
                        @foreach([['Abertos',$open,$openRate,'#f59e0b'],['Ganhos',$won,$wonRate,'#22c55e'],['Perdidos',$lost,$lostRate,'#ef4444']] as $s)
                            <div><div class="flex items-center justify-between"><span class="crm-muted text-xs font-bold">{{ $s[0] }}</span><strong class="text-xl">{{ $s[1] }}</strong></div><div class="crm-kpi-line mt-4"><span style="width:{{ min($s[2],100) }}%;background:{{ $s[3] }}"></span></div><div class="crm-muted mt-2 text-[11px]">{{ number_format($s[2],1,',','.') }}%</div></div>
                        @endforeach
                    </div>
                </div>
                <div class="crm-card">
                    <div class="crm-section-head"><div><h2 class="crm-section-title">Pendências</h2><p class="crm-section-subtitle">Ações que exigem atenção</p></div></div>
                    <div class="crm-body space-y-3"><div class="rounded-xl border border-[var(--crm-border)] bg-[var(--crm-card-2)] p-4"><div class="crm-muted text-xs">Atividades vencidas</div><div class="mt-2 text-3xl font-black">{{ $overdue_count ?? 0 }}</div></div><a href="{{ route('crm.agenda',['period'=>'overdue']) }}" class="crm-btn w-full">Ver agenda vencida</a></div>
                </div>
            </section>

            <section class="grid gap-4 xl:grid-cols-2">
                <div class="crm-card overflow-hidden">
                    <div class="crm-section-head"><div><h2 class="crm-section-title">Leads recentes</h2><p class="crm-section-subtitle">Últimas oportunidades adicionadas</p></div><a href="{{ route('crm.leads.index') }}" class="text-xs font-bold text-violet-400">Ver todos</a></div>
                    <div>
                        @forelse($recent_leads as $lead)
                            <a href="{{ route('crm.leads.show',$lead) }}" class="flex items-center justify-between gap-3 border-b border-[var(--crm-border)] px-4 py-3 last:border-0 hover:bg-[var(--crm-card-2)]"><div class="flex min-w-0 items-center gap-3"><div class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-violet-500/15 text-xs font-black text-violet-300">{{ mb_strtoupper(mb_substr($lead->name,0,1)) }}</div><div class="min-w-0"><div class="truncate text-sm font-bold">{{ $lead->name }}</div><div class="crm-muted truncate text-[11px]">{{ $lead->stage?->name ?? 'Sem etapa' }} · {{ $lead->owner?->name ?? 'Não atribuído' }}</div></div></div><strong class="whitespace-nowrap text-xs">R$ {{ number_format((float)$lead->value,2,',','.') }}</strong></a>
                        @empty<div class="crm-empty">Nenhum lead cadastrado.</div>@endforelse
                    </div>
                </div>
                <div class="crm-card">
                    <div class="crm-section-head"><div><h2 class="crm-section-title">Origem dos leads</h2><p class="crm-section-subtitle">Principais canais de aquisição</p></div></div>
                    <div class="crm-body space-y-4">
                        @forelse($source_stats as $source)
                            @php($pct = $total > 0 ? round(($source->total/$total)*100,1) : 0)
                            <div><div class="mb-2 flex items-center justify-between text-xs"><span class="font-bold">{{ $source->source }}</span><span class="crm-muted">{{ $source->total }} · {{ number_format($pct,1,',','.') }}%</span></div><div class="crm-kpi-line"><span style="width:{{ min($pct,100) }}%"></span></div></div>
                        @empty<div class="crm-empty">Sem dados de origem.</div>@endforelse
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
