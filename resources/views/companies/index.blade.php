@extends('layouts.smartbiz')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold">Empresas</h1>
        <p class="text-muted-sb">Gerencie todas as empresas da plataforma.</p>
    </div>

    <a href="{{ route('companies.create') }}" class="btn btn-primary">
        + Nova Empresa
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="sb-card mb-4">
    <form method="GET" action="{{ route('companies.index') }}" class="row g-3 align-items-end">
        <div class="col-md-10">
            <label class="form-label">Pesquisar</label>
            <input type="text" name="search" class="form-control" value="{{ $search ?? '' }}" placeholder="Nome, CNPJ, responsável ou cidade...">
        </div>
        <div class="col-md-2 d-grid">
            <button class="btn btn-primary">Buscar</button>
        </div>
    </form>
</div>

<div class="sb-card">
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Empresa</th>
                    <th>Responsável</th>
                    <th>Localização</th>
                    <th>Plano</th>
                    <th>Status</th>
                    <th width="210">Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse($companies as $company)
                    <tr>
                        <td>{{ $company->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 bg-primary d-flex align-items-center justify-content-center fw-bold" style="width:42px;height:42px;min-width:42px;">
                                    @if($company->logo)
                                        <img src="{{ asset('storage/' . $company->logo) }}" alt="Logo" class="rounded-3" style="width:42px;height:42px;object-fit:cover;">
                                    @else
                                        {{ strtoupper(substr($company->trade_name ?: $company->name, 0, 2)) }}
                                    @endif
                                </div>
                                <div>
                                    <strong>{{ $company->trade_name ?: $company->name }}</strong>
                                    <br>
                                    <small class="text-muted-sb">{{ $company->email ?: 'Sem e-mail' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $company->owner ?: '-' }}</td>
                        <td>{{ trim(($company->city ?: '') . '/' . ($company->state ?: ''), '/') ?: '-' }}</td>
                        <td>{{ $company->plan }}</td>
                        <td>
                            @if($company->status === 'Ativa')
                                <span class="badge bg-success">Ativa</span>
                            @elseif($company->status === 'Suspensa')
                                <span class="badge bg-warning text-dark">Suspensa</span>
                            @else
                                <span class="badge bg-danger">Cancelada</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('companies.show', $company) }}" class="btn btn-sm btn-outline-info">Ver</a>
                                <a href="{{ route('companies.edit', $company) }}" class="btn btn-sm btn-outline-light">Editar</a>
                                <form action="{{ route('companies.destroy', $company) }}" method="POST" onsubmit="return confirm('Deseja realmente arquivar esta empresa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">Nenhuma empresa cadastrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $companies->links() }}
    </div>
</div>

@endsection
