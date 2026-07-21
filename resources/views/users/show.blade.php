@extends('layouts.smartbiz')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold">Perfil do Usuário</h1>
        <p class="text-muted-sb">Informações e permissões de acesso.</p>
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-outline-light">Voltar</a>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="sb-card text-center">
            <div class="rounded-4 bg-primary d-inline-flex align-items-center justify-content-center fw-bold fs-2 mb-3" style="width:90px;height:90px;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <h3 class="fw-bold mb-1">{{ $user->name }}</h3>
            <p class="text-muted-sb mb-3">{{ $user->email }}</p>

            <span class="badge bg-success">Ativo</span>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="sb-card">
            <h5 class="fw-bold mb-4">Dados do acesso</h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <small class="text-muted-sb">Nome</small>
                    <div>{{ $user->name }}</div>
                </div>

                <div class="col-md-6">
                    <small class="text-muted-sb">E-mail</small>
                    <div>{{ $user->email }}</div>
                </div>

                <div class="col-md-6">
                    <small class="text-muted-sb">Criado em</small>
                    <div>{{ optional($user->created_at)->format('d/m/Y H:i') }}</div>
                </div>

                <div class="col-md-6">
                    <small class="text-muted-sb">Última atualização</small>
                    <div>{{ optional($user->updated_at)->format('d/m/Y H:i') }}</div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                    Editar Usuário
                </a>
            </div>
        </div>
    </div>
</div>

@endsection