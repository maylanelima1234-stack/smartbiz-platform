@extends('layouts.smartbiz')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold">{{ $company->trade_name ?: $company->name }}</h1>
        <p class="text-muted-sb mb-0">Workspace da empresa na SmartBiz.</p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('companies.edit', $company) }}" class="btn btn-primary">Editar</a>
        <a href="{{ route('companies.index') }}" class="btn btn-outline-light">Voltar</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-4">
        <div class="sb-card h-100">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="rounded-4 bg-primary d-flex align-items-center justify-content-center fw-bold" style="width:72px;height:72px;min-width:72px;">
                    @if($company->logo)
                        <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo" class="rounded-4" style="width:72px;height:72px;object-fit:cover;">
                    @else
                        {{ strtoupper(substr($company->trade_name ?: $company->name, 0, 2)) }}
                    @endif
                </div>
                <div>
                    <h4 class="fw-bold mb-1">{{ $company->trade_name ?: $company->name }}</h4>
                    <span class="badge {{ $company->status === 'Ativa' ? 'bg-success' : ($company->status === 'Suspensa' ? 'bg-warning text-dark' : 'bg-danger') }}">
                        {{ $company->status }}
                    </span>
                </div>
            </div>

            <p class="text-muted-sb mb-2"><strong class="text-white">Razão social:</strong> {{ $company->name }}</p>
            <p class="text-muted-sb mb-2"><strong class="text-white">CNPJ:</strong> {{ $company->cnpj ?: '-' }}</p>
            <p class="text-muted-sb mb-2"><strong class="text-white">Plano:</strong> {{ $company->plan }}</p>
            <p class="text-muted-sb mb-0"><strong class="text-white">Responsável:</strong> {{ $company->owner ?: '-' }}</p>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="row g-4">
            <div class="col-md-4"><div class="sb-card"><small class="text-muted-sb">Leads</small><h2 class="fw-bold mt-2">0</h2></div></div>
            <div class="col-md-4"><div class="sb-card"><small class="text-muted-sb">Campanhas</small><h2 class="fw-bold mt-2">0</h2></div></div>
            <div class="col-md-4"><div class="sb-card"><small class="text-muted-sb">Financeiro</small><h2 class="fw-bold mt-2">R$ 0</h2></div></div>
        </div>

        <div class="sb-card mt-4">
            <h5 class="fw-bold mb-3">Informações de contato</h5>
            <div class="row g-3 text-muted-sb">
                <div class="col-md-6"><strong class="text-white">E-mail:</strong> {{ $company->email ?: '-' }}</div>
                <div class="col-md-6"><strong class="text-white">Telefone:</strong> {{ $company->phone ?: '-' }}</div>
                <div class="col-md-6"><strong class="text-white">Site:</strong> {{ $company->website ?: '-' }}</div>
                <div class="col-md-6"><strong class="text-white">Cidade:</strong> {{ trim(($company->city ?: '') . '/' . ($company->state ?: ''), '/') ?: '-' }}</div>
                <div class="col-12"><strong class="text-white">Endereço:</strong> {{ $company->address ?: '-' }} {{ $company->number ? ', ' . $company->number : '' }}</div>
            </div>
        </div>

        <div class="sb-card mt-4">
            <h5 class="fw-bold mb-3">Próximos módulos</h5>
            <p class="text-muted-sb mb-0">CRM, Financeiro, Usuários, Contratos, Campanhas e Relatórios serão conectados a este workspace.</p>
        </div>
    </div>
</div>

@endsection
