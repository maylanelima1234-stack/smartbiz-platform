@extends('layouts.smartbiz')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold">Usuários</h1>
        <p class="text-muted-sb">Gerencie acessos, perfis e vínculos com empresas.</p>
    </div>

    <a href="{{ route('users.create') }}" class="btn btn-primary">
        + Novo Usuário
    </a>
</div>

@php
    $roles = [
        'admin' => 'Administrador',
        'admin_cto' => 'Administrador CTO',
        'admin_tm' => 'Administrador Tráfego',
        'gestor' => 'Gestor',
        'cliente_gestor' => 'Gestor da Empresa',
        'comercial' => 'Comercial',
        'vendedor' => 'Vendedor',
        'financeiro' => 'Financeiro',
        'cliente' => 'Cliente',
    ];
@endphp

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="sb-card mb-4">
    <form method="GET" action="{{ route('users.index') }}" class="row g-3 align-items-end">
        <div class="col-md-10">
            <label class="form-label">Pesquisar</label>
            <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Nome, e-mail, telefone, cargo ou perfil...">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Buscar</button>
        </div>
    </form>
</div>

<div class="sb-card">
    <table class="table table-dark table-hover align-middle mb-0">
        <thead>
            <tr>
                <th>Usuário</th>
                <th>Empresa</th>
                <th>Perfil</th>
                <th>Status</th>
                <th width="220">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-primary d-flex align-items-center justify-content-center fw-bold" style="width:42px;height:42px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <strong>{{ $user->name }}</strong><br>
                                <small class="text-muted-sb">{{ $user->email }}</small>
                            </div>
                        </div>
                    </td>

                    <td>{{ $user->company->trade_name ?? $user->company->name ?? '-' }}</td>

                    <td>
                        {{ $roles[$user->role ?? ''] ?? ($user->role ?? '-') }}
                    </td>

                    <td>
                        @if(($user->status ?? 'Ativo') === 'Ativo')
                            <span class="badge bg-success">Ativo</span>
                        @else
                            <span class="badge bg-danger">Bloqueado</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-info">Ver</a>

                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-light">Editar</a>

                        @if(auth()->id() !== $user->id)
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja realmente remover este usuário?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Excluir</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-5">Nenhum usuário encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>

@endsection