@extends('layouts.smartbiz')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold">CRM</h1>
        <p class="text-muted-sb">Pipeline comercial da SmartBiz.</p>
    </div>
    <a href="{{ route('crm.leads.create') }}" class="btn btn-primary">+ Novo Lead</a>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4"><div class="sb-card"><small class="text-muted-sb">Leads Totais</small><h2 class="fw-bold mt-2">{{ $totalLeads }}</h2></div></div>
    <div class="col-md-4"><div class="sb-card"><small class="text-muted-sb">Em Aberto</small><h2 class="fw-bold mt-2">{{ $openLeads }}</h2></div></div>
    <div class="col-md-4"><div class="sb-card"><small class="text-muted-sb">Convertidos</small><h2 class="fw-bold mt-2 text-success">{{ $convertedLeads }}</h2></div></div>
</div>

@if(!$pipeline)
    <div class="alert alert-warning">Nenhum pipeline foi criado ainda. Rode o seeder indicado no README do pacote.</div>
@else
    <div class="row g-3">
        @foreach($pipeline->stages as $stage)
            <div class="col-lg">
                <div class="sb-card h-100">
                    <h6 class="fw-bold mb-3">{{ $stage->name }}</h6>
                    @forelse($stage->leads as $lead)
                        <a href="{{ route('crm.leads.show', $lead) }}" class="d-block text-decoration-none text-white mb-3 p-3 rounded-3" style="background:#12071C;border:1px solid rgba(255,255,255,.08);">
                            <strong>{{ $lead->name }}</strong><br>
                            <small class="text-muted-sb">{{ $lead->company->name ?? 'Sem empresa' }}</small><br>
                            <small class="text-success"><x-smart.money :value="$lead->value" /></small>
                        </a>
                    @empty
                        <p class="text-muted-sb small mb-0">Sem leads nesta etapa.</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
