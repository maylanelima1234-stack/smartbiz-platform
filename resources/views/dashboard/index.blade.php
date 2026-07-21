@extends('layouts.smartbiz')

@section('title', 'Dashboard | SmartBiz')

@section('content')
<div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="sb-page-title mb-0">Bem-vinda, {{ strtok(auth()->user()->name ?? 'Usuário', ' ') }} 👋</h1>
        <p class="sb-page-subtitle">Acompanhe os principais números e movimentações da sua operação.</p>
    </div>
    <div class="d-flex gap-2">
        <x-smart.button variant="secondary" :href="route('companies.create')">Nova empresa</x-smart.button>
        <x-smart.button :href="route('leads.create')">Novo lead</x-smart.button>
    </div>
</div>

<div class="row g-3 g-xl-4 mb-4">
    <div class="col-sm-6 col-xl-3"><x-smart.metric label="Empresas" :value="$metrics['companies']" trend="Ativas" caption=" na plataforma" /></div>
    <div class="col-sm-6 col-xl-3"><x-smart.metric label="Leads" :value="$metrics['leads']" trend="CRM" caption=" total cadastrado" /></div>
    <div class="col-sm-6 col-xl-3"><x-smart.metric label="Valor em oportunidades" :value="$metrics['pipeline_value']" trend="Potencial" caption=" comercial" /></div>
    <div class="col-sm-6 col-xl-3"><x-smart.metric label="Acompanhamentos" :value="$metrics['follow_ups']" trend="Atenção" trend-type="warning" caption=" pendentes" /></div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <x-smart.card title="Visão de crescimento" subtitle="Leads adicionados nos últimos períodos">
            <x-slot:actions><x-smart.badge variant="primary">Últimos 7 períodos</x-smart.badge></x-slot:actions>
            <div class="sb-chart-placeholder" aria-label="Gráfico ilustrativo">
                @foreach([35,52,44,67,58,81,73,92,76,100] as $height)
                    <div class="sb-chart-bar" style="height: {{ $height }}%"></div>
                @endforeach
            </div>
            <div class="d-flex justify-content-between small sb-muted mt-3"><span>Início</span><span>Hoje</span></div>
        </x-smart.card>
    </div>

    <div class="col-xl-4">
        <x-smart.card title="Saúde da operação" subtitle="Resumo dos módulos principais">
            @foreach([
                ['CRM', min(100, $metrics['leads'] * 5), 'Base comercial'],
                ['Empresas', min(100, $metrics['companies'] * 10), 'Carteira ativa'],
                ['Acompanhamentos', $metrics['follow_ups'] > 0 ? 68 : 100, 'Rotina de contato'],
            ] as [$name,$percent,$description])
                <div class="mb-4">
                    <div class="d-flex justify-content-between mb-2"><div><strong>{{ $name }}</strong><div class="small sb-muted">{{ $description }}</div></div><strong>{{ $percent }}%</strong></div>
                    <div class="sb-progress"><div class="sb-progress-bar" style="width:{{ $percent }}%"></div></div>
                </div>
            @endforeach
            <x-smart.button variant="secondary" :href="route('crm.index')" class="w-100">Abrir CRM</x-smart.button>
        </x-smart.card>
    </div>

    <div class="col-xl-7">
        <x-smart.card title="Leads recentes" subtitle="Últimas oportunidades registradas">
            <x-slot:actions><a href="{{ route('leads.index') }}" class="small fw-bold text-decoration-none">Ver todos</a></x-slot:actions>
            <ul class="sb-list">
                @forelse($recentLeads as $lead)
                    <li class="sb-list-item">
                        <div class="sb-avatar" style="width:40px;height:40px">{{ strtoupper(substr($lead->name, 0, 1)) }}</div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-bold text-truncate">{{ $lead->name }}</div>
                            <div class="small sb-muted text-truncate">{{ $lead->company?->trade_name ?? $lead->company?->name ?? $lead->source }}</div>
                        </div>
                        <div class="text-end">
                            <x-smart.badge :variant="$lead->priority === 'Alta' ? 'warning' : 'neutral'">{{ $lead->status }}</x-smart.badge>
                            <div class="small sb-muted mt-1">{{ $lead->created_at?->diffForHumans() }}</div>
                        </div>
                    </li>
                @empty
                    <li class="py-4 text-center sb-muted">Nenhum lead cadastrado ainda.</li>
                @endforelse
            </ul>
        </x-smart.card>
    </div>

    <div class="col-xl-5">
        <x-smart.card title="Atividades recentes" subtitle="Movimentações da plataforma">
            <ul class="sb-list">
                @forelse($recentCompanies as $company)
                    <li class="sb-list-item">
                        <span class="sb-list-dot"></span>
                        <div class="flex-grow-1">
                            <div class="fw-bold">Empresa cadastrada</div>
                            <div class="small sb-muted">{{ $company->trade_name ?: $company->name }}</div>
                        </div>
                        <div class="small sb-muted">{{ $company->created_at?->diffForHumans() }}</div>
                    </li>
                @empty
                    <li class="py-4 text-center sb-muted">Nenhuma atividade recente.</li>
                @endforelse
            </ul>
        </x-smart.card>
    </div>
</div>
@endsection
