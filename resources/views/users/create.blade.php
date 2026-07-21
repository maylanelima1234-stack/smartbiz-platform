@extends('layouts.smartbiz')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold">Novo Usuário</h1>
        <p class="text-muted-sb">Cadastre um novo acesso para a plataforma.</p>
    </div>

    <a href="{{ route('users.index') }}" class="btn btn-outline-light">Voltar</a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <strong>Revise os campos abaixo.</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="sb-card">
    <form method="POST" action="{{ route('users.store') }}" onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerText='Salvando...';">
        @csrf

        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label">Nome *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">E-mail *</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Telefone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Cargo</label>
                <input type="text" name="position" value="{{ old('position') }}" class="form-control" placeholder="Ex: Gestor de tráfego">
            </div>

            <div class="col-md-4">
                <label class="form-label">Empresa</label>
                <select name="company_id" class="form-select">
                    <option value="">SmartBiz / Sem empresa</option>

                    @foreach($companies ?? [] as $company)
                        <option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>
                            {{ $company->trade_name ?: $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Perfil *</label>
                <select name="role" class="form-select" required>
                    <option value="admin" @selected(old('role') === 'admin')>Administrador</option>
                    <option value="gestor" @selected(old('role') === 'gestor')>Gestor</option>
                    <option value="comercial" @selected(old('role') === 'comercial')>Comercial</option>
                    <option value="financeiro" @selected(old('role') === 'financeiro')>Financeiro</option>
                    <option value="vendedor" @selected(old('role') === 'vendedor')>Vendedor</option>
                    <option value="cliente" @selected(old('role', 'cliente') === 'cliente')>Cliente</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Status *</label>
                <select name="status" class="form-select" required>
                    <option value="Ativo" @selected(old('status', 'Ativo') === 'Ativo')>Ativo</option>
                    <option value="Bloqueado" @selected(old('status') === 'Bloqueado')>Bloqueado</option>
                </select>
            </div>

            <div class="col-md-4"></div>

            <div class="col-md-6">
                <label class="form-label">Senha *</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Confirmar senha *</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary px-5">Salvar Usuário</button>
        </div>
    </form>
</div>

@endsection