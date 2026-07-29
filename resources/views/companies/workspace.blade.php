@extends('layouts.smartbiz')

@section('title', 'Workspace | SmartBiz')
@section('page_title', 'Workspace da Empresa')
@section('page_subtitle', $company->trade_name ?: $company->name)

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-3">
            @if($company->logo)
                <img src="{{ asset('storage/'.$company->logo) }}" alt="Logo" style="width:64px;height:64px;object-fit:cover;border-radius:16px;">
            @else
                <div class="sb-brand-mark" style="width:64px;height:64px;border-radius:16px;">{{ strtoupper(substr($company->trade_name ?: $company->name, 0, 2)) }}</div>
            @endif
            <div>
                <h1 class="sb-page-title mb-1">{{ $company->trade_name ?: $company->name }}</h1>
                <p class="sb-page-subtitle mb-0">{{ $company->city ?: 'Cidade não informada' }}{{ $company->state ? ' / '.$company->state : '' }} • Plano {{ $company->plan ?: 'não definido' }}</p>
            </div>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <form method="POST" action="{{ route('companies.activate', $company) }}">
            @csrf
            <x-smart.button type="submit" variant="secondary">Usar esta empresa</x-smart.button>
        </form>
        @if(in_array(auth()->user()?->role, ['super_admin', 'admin_cto'], true))
            <x-smart.button variant="secondary" :href="route('companies.edit', $company)">Editar empresa</x-smart.button>
        @endif
        <x-smart.button :href="route('crm.leads.create', ['company_id' => $company->id])">Novo lead</x-smart.button>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3"><x-smart.metric label="Health Score" :value="$healthScore.'%'" trend="Operação" caption=" saúde atual" /></div>
    <div class="col-sm-6 col-xl-3"><x-smart.metric label="Equipe" :value="$metrics['team']" trend="Acessos" caption=" usuários vinculados" /></div>
    <div class="col-sm-6 col-xl-3"><x-smart.metric label="Leads" :value="$metrics['leads']" trend="CRM" caption=" cadastrados" /></div>
    <div class="col-sm-6 col-xl-3"><x-smart.metric label="Follow-ups" :value="$metrics['follow_ups']" trend="Próximos 7 dias" trend-type="warning" caption=" pendentes" /></div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-8">
        <x-smart.card title="Visão da empresa" subtitle="Configuração inicial do workspace">
            <div class="row g-3">
                @foreach([
                    ['Dados da empresa', filled($company->email) && filled($company->phone), 'Contato e identificação'],
                    ['Equipe vinculada', $metrics['team'] > 0, 'Usuários com acesso'],
                    ['CRM iniciado', $metrics['leads'] > 0, 'Primeiros leads cadastrados'],
                    ['Plano definido', filled($company->plan), 'Licenciamento da empresa'],
                ] as [$label, $done, $description])
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <strong>{{ $label }}</strong>
                                    <div class="small sb-muted mt-1">{{ $description }}</div>
                                </div>
                                <x-smart.badge :variant="$done ? 'success' : 'warning'">{{ $done ? 'Concluído' : 'Pendente' }}</x-smart.badge>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-smart.card>
    </div>

    <div class="col-xl-4">
        <x-smart.card title="Status operacional" subtitle="Saúde calculada automaticamente">
            <div class="text-center py-3">
                <div style="font-size:3rem;font-weight:800;line-height:1;">{{ $healthScore }}%</div>
                <div class="sb-muted mt-2">{{ $healthScore >= 80 ? 'Operação saudável' : ($healthScore >= 50 ? 'Operação em evolução' : 'Implantação necessária') }}</div>
            </div>
            <div class="sb-progress mb-3"><div class="sb-progress-bar" style="width:{{ $healthScore }}%"></div></div>
            <div class="small sb-muted">O indicador considera cadastro, equipe, CRM e informações de contato. Nenhum dado financeiro é exibido neste workspace.</div>
        </x-smart.card>
    </div>
</div>

<div class="row g-3">
    <div class="col-xl-7">
        <x-smart.card title="Leads recentes" subtitle="Últimas oportunidades desta empresa">
            @forelse($recentLeads as $lead)
                <a href="{{ route('crm.leads.show', $lead) }}" class="d-flex justify-content-between align-items-center gap-3 py-3 border-bottom text-decoration-none text-reset">
                    <div>
                        <strong>{{ $lead->name }}</strong>
                        <div class="small sb-muted">{{ $lead->phone ?: $lead->email ?: 'Contato não informado' }}</div>
                    </div>
                    <x-smart.badge variant="primary">{{ $lead->status ?: 'novo' }}</x-smart.badge>
                </a>
            @empty
                <x-smart.empty-state title="Nenhum lead nesta empresa" description="Cadastre a primeira oportunidade para iniciar o acompanhamento comercial." />
            @endforelse
        </x-smart.card>
    </div>

    <div class="col-xl-5">
        <x-smart.card title="Minha equipe" subtitle="Usuários relacionados à empresa">
            @forelse($team as $member)
                <div class="d-flex align-items-center gap-3 py-3 border-bottom">
                    <div class="sb-brand-mark" style="width:42px;height:42px;border-radius:12px;font-size:.8rem;">{{ strtoupper(substr($member->name, 0, 2)) }}</div>
                    <div class="flex-grow-1">
                        <strong>{{ $member->name }}</strong>
                        <div class="small sb-muted">{{ $member->position ?: $member->roleLabel() }}</div>
                    </div>
                    <x-smart.badge :variant="$member->status === 'Ativo' ? 'success' : 'warning'">{{ $member->status }}</x-smart.badge>
                </div>
            @empty
                <x-smart.empty-state title="Equipe não vinculada" description="Vincule usuários para formar a equipe desta empresa." />
            @endforelse
        </x-smart.card>
    </div>
</div>
@endsection
