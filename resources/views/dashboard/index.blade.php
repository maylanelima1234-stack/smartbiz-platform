@extends('layouts.smartbiz')

@section('title', 'Centro de Operações | SmartBiz')
@section('page_title', 'Centro de Operações')
@section('page_subtitle', 'SmartBiz Enterprise Workspace')

@section('content')
<div class="sb-dashboard-welcome" x-data="smartBizClock()" x-init="start()">
    <div>
        <h1 class="sb-page-title mb-0"><span x-text="greeting"></span>, {{ strtok(auth()->user()->name ?? 'Usuário', ' ') }} 👋</h1>
        <p class="sb-page-subtitle"><span x-text="formattedDate"></span> • <span x-text="formattedTime"></span> (Brasília)</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <x-smart.button variant="secondary" :href="route('operations.feed')">Ver feed completo</x-smart.button>
        @if($isCto ?? false)<x-smart.button variant="secondary" :href="route('companies.create')">Nova empresa</x-smart.button>@endif
        <x-smart.button :href="route('crm.leads.create')">Novo lead</x-smart.button>
    </div>
</div>

@if(($isInternal ?? false) && isset($availableCompanies) && $availableCompanies->isNotEmpty())
<div class="mb-4"><x-smart.card title="Empresa ativa" subtitle="Alterne a operação sem trocar de login"><div class="d-flex flex-wrap gap-2">@foreach($availableCompanies as $company)<form method="POST" action="{{ route('companies.activate', $company) }}">@csrf<button type="submit" class="btn {{ optional($activeCompany)->id === $company->id ? 'btn-primary' : 'btn-outline-secondary' }}">{{ $company->trade_name ?: $company->name }}</button></form>@endforeach</div></x-smart.card></div>
@endif

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3"><x-smart.metric label="Leads ativos" :value="$metrics['leads']" trend="CRM" caption=" oportunidades" /></div>
    <div class="col-sm-6 col-xl-3"><x-smart.metric label="Valor no pipeline" :value="$metrics['pipeline_value']" trend="Potencial" caption=" comercial" /></div>
    <div class="col-sm-6 col-xl-3"><x-smart.metric label="Conversão" :value="$metrics['conversion_rate']" trend="{{ $metrics['won'] }} vendas" caption=" concluídas" /></div>
    <div class="col-sm-6 col-xl-3"><x-smart.metric label="Atrasadas" :value="$metrics['overdue_activities']" trend="Atenção" trend-type="warning" caption=" atividades" /></div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-7">
        <x-smart.card title="O que precisa da sua atenção" subtitle="Prioridades identificadas automaticamente">
            <x-slot:actions><x-smart.badge :variant="$attentionItems->isEmpty() ? 'success' : 'warning'">{{ $attentionItems->count() }} itens</x-smart.badge></x-slot:actions>
            <div class="sb-attention-list">
                @forelse($attentionItems as $item)
                    <a href="{{ $item['url'] }}" class="sb-attention-item sb-attention-{{ $item['level'] }}">
                        <span class="sb-attention-indicator"></span>
                        <span class="flex-grow-1 min-w-0"><strong class="d-block text-truncate">{{ $item['title'] }}</strong><small class="sb-muted">{{ $item['description'] }}</small></span>
                        <span class="small fw-bold">{{ $item['label'] }} →</span>
                    </a>
                @empty
                    <div class="sb-operation-clear"><strong>Operação em dia</strong><span>Nenhuma urgência identificada agora.</span></div>
                @endforelse
            </div>
        </x-smart.card>
    </div>
    <div class="col-xl-5">
        <x-smart.card title="Agenda de hoje" subtitle="Compromissos e acompanhamentos">
            <div class="sb-agenda-summary">
                <div><strong>{{ $metrics['today_activities'] }}</strong><span>Para hoje</span></div>
                <div><strong>{{ $metrics['overdue_activities'] }}</strong><span>Vencidas</span></div>
                <div><strong>{{ $metrics['follow_ups'] }}</strong><span>Próximos 7 dias</span></div>
            </div>
            <x-smart.button variant="secondary" :href="route('crm.agenda')" class="w-100 mt-4">Abrir agenda</x-smart.button>
        </x-smart.card>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-8">
        <x-smart.card title="Entrada de leads" subtitle="Últimos 7 dias">
            <div class="sb-real-chart">
                @foreach($leadSeries as $point)
                    @php($height = max(6, round(($point['value'] / $maxLeadSeries) * 100)))
                    <div class="sb-real-chart-column" title="{{ $point['value'] }} lead(s) em {{ $point['date'] }}">
                        <span class="sb-real-chart-value">{{ $point['value'] }}</span>
                        <div class="sb-real-chart-bar" style="height: {{ $height }}%"></div>
                        <small>{{ $point['label'] }}</small>
                    </div>
                @endforeach
            </div>
        </x-smart.card>
    </div>
    <div class="col-xl-4">
        <x-smart.card title="Pulso comercial" subtitle="Indicadores essenciais">
            @foreach([
                ['Conversão', (float) str_replace(',', '.', $metrics['conversion_rate']), $metrics['conversion_rate']],
                ['Follow-ups programados', min(100, $metrics['follow_ups'] * 10), $metrics['follow_ups']],
                ['Atividades em dia', $metrics['overdue_activities'] === 0 ? 100 : max(10, 100 - ($metrics['overdue_activities'] * 10)), $metrics['overdue_activities'] === 0 ? '100%' : 'Requer atenção'],
            ] as [$name,$percent,$value])
                <div class="mb-4"><div class="d-flex justify-content-between mb-2"><strong>{{ $name }}</strong><strong>{{ $value }}</strong></div><div class="sb-progress"><div class="sb-progress-bar" style="width:{{ min(100, $percent) }}%"></div></div></div>
            @endforeach
            <x-smart.button variant="secondary" :href="route('crm.dashboard')" class="w-100">Dashboard comercial</x-smart.button>
        </x-smart.card>
    </div>
</div>

<div class="row g-3">
    <div class="col-xl-7">
        <x-smart.card title="Feed inteligente" subtitle="O que aconteceu na operação">
            <x-slot:actions><a href="{{ route('operations.feed') }}" class="small fw-bold text-decoration-none">Ver histórico</a></x-slot:actions>
            <ul class="sb-list">
                @forelse($feed as $event)
                    <li class="sb-list-item"><span class="sb-list-dot"></span><div class="flex-grow-1 min-w-0"><div class="fw-bold text-truncate">{{ $event->title }}</div><div class="small sb-muted text-truncate">{{ $event->lead?->name ? $event->lead->name.' • ' : '' }}{{ $event->description }}</div></div><div class="small sb-muted text-nowrap">{{ $event->created_at?->diffForHumans() }}</div></li>
                @empty<li class="py-4 text-center sb-muted">As movimentações do CRM aparecerão aqui.</li>@endforelse
            </ul>
        </x-smart.card>
    </div>
    <div class="col-xl-5">
        <x-smart.card title="Leads recentes" subtitle="Últimas oportunidades registradas">
            <x-slot:actions><a href="{{ route('crm.leads.index') }}" class="small fw-bold text-decoration-none">Ver todos</a></x-slot:actions>
            <ul class="sb-list">@forelse($recentLeads as $lead)<li class="sb-list-item"><div class="sb-avatar" style="width:36px;height:36px">{{ strtoupper(substr($lead->name,0,1)) }}</div><div class="flex-grow-1 min-w-0"><div class="fw-bold text-truncate">{{ $lead->name }}</div><div class="small sb-muted text-truncate">{{ $lead->owner?->name ?? $lead->source ?? 'Sem responsável' }}</div></div><div class="text-end"><x-smart.badge :variant="$lead->priority === 'Alta' ? 'warning' : 'neutral'">{{ $lead->status }}</x-smart.badge><div class="small sb-muted mt-1">{{ $lead->created_at?->diffForHumans() }}</div></div></li>@empty<li class="py-4 text-center sb-muted">Nenhum lead cadastrado ainda.</li>@endforelse</ul>
        </x-smart.card>
    </div>
</div>
@endsection

@push('scripts')
<script>
function smartBizClock(){return{greeting:'',formattedDate:'',formattedTime:'',timer:null,start(){this.update();this.timer=setInterval(()=>this.update(),30000)},update(){const now=new Date(new Date().toLocaleString('en-US',{timeZone:'America/Sao_Paulo'}));const h=now.getHours();this.greeting=h>=5&&h<12?'Bom dia':h>=12&&h<18?'Boa tarde':'Boa noite';this.formattedDate=new Intl.DateTimeFormat('pt-BR',{timeZone:'America/Sao_Paulo',weekday:'long',day:'2-digit',month:'long',year:'numeric'}).format(new Date());this.formattedDate=this.formattedDate.charAt(0).toUpperCase()+this.formattedDate.slice(1);this.formattedTime=new Intl.DateTimeFormat('pt-BR',{timeZone:'America/Sao_Paulo',hour:'2-digit',minute:'2-digit',hour12:false}).format(new Date());}}}
</script>
@endpush
