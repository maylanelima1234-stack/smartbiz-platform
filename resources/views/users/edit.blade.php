@extends('layouts.smartbiz')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold">Editar Usuário</h1>
        <p class="text-muted-sb">Atualize os dados de acesso.</p>
    </div>

    <a href="{{ route('users.index') }}" class="btn btn-outline-light">Voltar</a>
</div>

<div class="sb-card">
    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">Nome</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Nova senha</label>
                <input type="password" name="password" class="form-control" placeholder="Deixe vazio para manter a atual">
            </div>
        </div>

        <div class="mt-4 text-end">
            <button class="btn btn-primary px-5">Salvar Alterações</button>
        </div>
    </form>
</div>

@endsection