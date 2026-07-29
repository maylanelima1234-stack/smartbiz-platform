@extends('layouts.smartbiz')
@include('users.partials.theme')

@section('content')
@php
    $roles = [
        'admin' => 'Administrador', 'admin_cto' => 'Administrador CTO',
        'admin_tm' => 'Administrador Tráfego', 'gestor' => 'Gestor',
        'cliente_gestor' => 'Gestor da Empresa', 'comercial' => 'Comercial',
        'vendedor' => 'Vendedor', 'financeiro' => 'Financeiro', 'cliente' => 'Cliente',
    ];
    $collection = collect(method_exists($users, 'items') ? $users->items() : $users);
    $activeCount = $collection->filter(fn($u) => ($u->status ?? 'Ativo') === 'Ativo')->count();
    $blockedCount = $collection->filter(fn($u) => ($u->status ?? 'Ativo') !== 'Ativo')->count();
    $adminCount = $collection->filter(fn($u) => in_array($u->role, ['admin','admin_cto','admin_tm'], true))->count();
@endphp

<div class="team-shell">
    <div class="team-page-head">
        <div>
            <h1>Gerenciar equipe</h1>
            <p>Controle acessos, vínculos, perfis e status dos usuários.</p>
        </div>
        <a href="{{ route('users.create') }}" class="team-btn team-btn-primary">＋ Novo usuário</a>
    </div>

    @if(session('success')) <div class="team-alert team-alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="team-alert team-alert-danger">{{ session('error') }}</div> @endif

    <div class="team-metrics">
        <div class="team-card team-metric"><div class="team-metric-label">Usuários exibidos</div><div class="team-metric-value">{{ $collection->count() }}</div></div>
        <div class="team-card team-metric"><div class="team-metric-label"><span class="team-metric-dot" style="background:#22c55e"></span>Ativos</div><div class="team-metric-value">{{ $activeCount }}</div></div>
        <div class="team-card team-metric"><div class="team-metric-label"><span class="team-metric-dot" style="background:#7c3aed"></span>Administradores</div><div class="team-metric-value">{{ $adminCount }}</div></div>
        <div class="team-card team-metric"><div class="team-metric-label"><span class="team-metric-dot" style="background:#ef4444"></span>Bloqueados</div><div class="team-metric-value">{{ $blockedCount }}</div></div>
    </div>

    <form method="GET" action="{{ route('users.index') }}" class="team-card team-toolbar">
        <div class="team-search"><span>⌕</span><input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por nome, e-mail, telefone, cargo ou perfil..."></div>
        <button class="team-btn team-btn-primary" type="submit">Buscar</button>
        @if(!empty($search)) <a class="team-btn team-btn-ghost" href="{{ route('users.index') }}">Limpar</a> @endif
    </form>

    <div class="team-card">
        <div class="team-table-wrap">
            <table class="team-table">
                <thead><tr><th>Usuário</th><th>Empresa</th><th>Perfil</th><th>Status</th><th>Último acesso</th><th>Ações</th></tr></thead>
                <tbody>
                @forelse($users as $user)
                    @php $initials = collect(explode(' ', trim($user->name)))->filter()->take(2)->map(fn($p)=>mb_strtoupper(mb_substr($p,0,1)))->implode(''); @endphp
                    <tr>
                        <td><div class="team-user"><div class="team-avatar">@if(!empty($user->avatar_url))<img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" onerror="this.remove(); this.parentElement.classList.add('avatar-load-failed');">@else{{ $initials ?: 'U' }}@endif @if(($user->status ?? 'Ativo')==='Ativo')<span class="team-avatar-dot"></span>@endif</div><div><div class="team-name">{{ $user->name }}</div><div class="team-sub">{{ $user->email }}</div>@if($user->position)<div class="team-sub">{{ $user->position }}</div>@endif</div></div></td>
                        <td>{{ $user->company->trade_name ?? $user->company->name ?? 'SmartBiz' }}</td>
                        <td><span class="team-badge team-badge-role">{{ $roles[$user->role ?? ''] ?? ($user->role ?? '-') }}</span></td>
                        <td>@if(($user->status ?? 'Ativo')==='Ativo')<span class="team-badge team-badge-success">● Ativo</span>@else<span class="team-badge team-badge-danger">● Bloqueado</span>@endif</td>
                        <td><div class="team-sub">{{ optional($user->last_login_at)->format('d/m/Y H:i') ?? 'Ainda não acessou' }}</div></td>
                        <td><div class="team-actions"><a href="{{ route('users.show',$user) }}" class="team-icon-btn">Ver</a><a href="{{ route('users.edit',$user) }}" class="team-icon-btn team-icon-edit">Editar</a>@if(auth()->id()!==$user->id)<form action="{{ route('users.destroy',$user) }}" method="POST" onsubmit="return confirm('Deseja realmente remover este usuário?')">@csrf @method('DELETE')<button class="team-icon-btn team-icon-danger" type="submit">Excluir</button></form>@endif</div></td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="team-empty"><strong>Nenhum usuário encontrado.</strong><br>Cadastre um novo acesso ou ajuste a pesquisa.</div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($users,'links'))<div class="team-pagination">{{ $users->withQueryString()->links() }}</div>@endif
    </div>
</div>
@endsection
