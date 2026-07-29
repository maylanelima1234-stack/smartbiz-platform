@extends('layouts.smartbiz')
@include('users.partials.theme')

@section('content')
@php
$roles=['admin'=>'Administrador','admin_cto'=>'Administrador CTO','admin_tm'=>'Administrador Tráfego','gestor'=>'Gestor','cliente_gestor'=>'Gestor da Empresa','comercial'=>'Comercial','vendedor'=>'Vendedor','financeiro'=>'Financeiro','cliente'=>'Cliente'];
$initials=collect(explode(' ',trim($user->name)))->filter()->take(2)->map(fn($p)=>mb_strtoupper(mb_substr($p,0,1)))->implode('');
@endphp
<div class="team-shell">
    <div class="team-page-head"><div><h1>Perfil do usuário</h1><p>Dados de acesso, vínculo e atividade na plataforma.</p></div><div class="d-flex gap-2"><a href="{{ route('users.index') }}" class="team-btn team-btn-ghost">← Voltar</a><a href="{{ route('users.edit',$user) }}" class="team-btn team-btn-primary">Editar usuário</a></div></div>
    <div class="team-profile-grid">
        <div class="team-card team-profile-main">
            <div class="team-avatar team-avatar-lg">@if(!empty($user->avatar_url))<img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" onerror="this.remove(); this.parentElement.classList.add('avatar-load-failed');">@else{{ $initials ?: 'U' }}@endif @if(($user->status ?? 'Ativo')==='Ativo')<span class="team-avatar-dot"></span>@endif</div>
            <div class="team-profile-name">{{ $user->name }}</div><div class="team-sub">{{ $user->position ?: 'Cargo não informado' }}</div><div class="team-sub mb-3">{{ $user->company->trade_name ?? $user->company->name ?? 'SmartBiz' }}</div>
            @if(($user->status ?? 'Ativo')==='Ativo')<span class="team-badge team-badge-success">● Ativo</span>@else<span class="team-badge team-badge-danger">● Bloqueado</span>@endif
            <div class="mt-3"><span class="team-badge team-badge-role">{{ $roles[$user->role ?? ''] ?? ($user->role ?? '-') }}</span></div>
        </div>
        <div class="team-card team-profile-info">
            <div class="team-section-title">Dados do acesso</div>
            <div class="team-info-grid">
                <div class="team-info-item"><div class="team-info-label">E-mail</div><div class="team-info-value">{{ $user->email }}</div></div>
                <div class="team-info-item"><div class="team-info-label">Telefone</div><div class="team-info-value">{{ $user->phone ?: 'Não informado' }}</div></div>
                <div class="team-info-item"><div class="team-info-label">Empresa</div><div class="team-info-value">{{ $user->company->trade_name ?? $user->company->name ?? 'SmartBiz / Sem empresa' }}</div></div>
                <div class="team-info-item"><div class="team-info-label">Perfil</div><div class="team-info-value">{{ $roles[$user->role ?? ''] ?? ($user->role ?? '-') }}</div></div>
                <div class="team-info-item"><div class="team-info-label">Último acesso</div><div class="team-info-value">{{ optional($user->last_login_at)->format('d/m/Y H:i') ?? 'Ainda não acessou' }}</div></div>
                <div class="team-info-item"><div class="team-info-label">Criado em</div><div class="team-info-value">{{ optional($user->created_at)->format('d/m/Y H:i') }}</div></div>
                <div class="team-info-item"><div class="team-info-label">Última atualização</div><div class="team-info-value">{{ optional($user->updated_at)->format('d/m/Y H:i') }}</div></div>
                <div class="team-info-item"><div class="team-info-label">Status</div><div class="team-info-value">{{ $user->status ?? 'Ativo' }}</div></div>
            </div>
        </div>
    </div>
</div>
@endsection
