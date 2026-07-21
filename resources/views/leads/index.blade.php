@extends('layouts.smartbiz')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold">Leads</h1>
        <p class="text-muted-sb">Gerencie os contatos e oportunidades comerciais.</p>
    </div>
    <a href="{{ route('leads.create') }}" class="btn btn-primary">+ Novo Lead</a>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<div class="sb-card mb-4">
    <form method="GET" action="{{ route('leads.index') }}" class="row g-3 align-items-end">
        <div class="col-md-10"><label class="form-label">Pesquisar</label><input type="text" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Nome, email ou telefone..."></div>
        <div class="col-md-2"><button class="btn btn-primary w-100">Buscar</button></div>
    </form>
</div>

<div class="sb-card">
    <table class="table table-dark table-hover align-middle mb-0">
        <thead><tr><th>Lead</th><th>Empresa</th><th>Etapa</th><th>Responsável</th><th>Valor</th><th width="220">Ações</th></tr></thead>
        <tbody>
            @forelse($leads as $lead)
                <tr>
                    <td><strong>{{ $lead->name }}</strong><br><small class="text-muted-sb">{{ $lead->phone }} {{ $lead->email ? '· '.$lead->email : '' }}</small></td>
                    <td>{{ $lead->company->name ?? '-' }}</td>
                    <td>{{ $lead->stage->name ?? '-' }}</td>
                    <td>{{ $lead->assignedUser->name ?? '-' }}</td>
                    <td>R$ {{ number_format($lead->value,2,',','.') }}</td>
                    <td>
                        <a href="{{ route('leads.show', $lead) }}" class="btn btn-sm btn-outline-info">Ver</a>
                        <a href="{{ route('leads.edit', $lead) }}" class="btn btn-sm btn-outline-light">Editar</a>
                        <form action="{{ route('leads.destroy', $lead) }}" method="POST" class="d-inline" onsubmit="return confirm('Remover este lead?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Excluir</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center py-5">Nenhum lead encontrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">{{ $leads->links() }}</div>
</div>
@endsection
