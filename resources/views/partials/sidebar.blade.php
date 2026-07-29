<aside class="sb-sidebar" aria-label="Navegação principal">
    <div class="sb-brand">
        <div class="sb-brand-mark">SB</div>
        <div class="sb-hide-collapsed">
            <div class="sb-brand-title">SmartBiz</div>
            <span class="sb-brand-subtitle">Enterprise OS</span>
        </div>
    </div>

    <div class="sb-sidebar-search sb-hide-collapsed">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.3-3.3"/></svg>
        <input type="search" x-model="menuQuery" placeholder="Buscar no menu..." aria-label="Buscar no menu">
        <kbd>Ctrl K</kbd>
    </div>

    <nav class="sb-nav">
        <div class="sb-nav-label sb-hide-collapsed">Visão geral</div>

        <a x-show="matchesMenu('dashboard visão geral início')" href="{{ route('dashboard') }}"
           class="sb-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg class="sb-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 11.5 12 4l9 7.5"/><path d="M5 10.5V20h14v-9.5"/><path d="M9 20v-6h6v6"/></svg>
            <span class="sb-hide-collapsed">Dashboard</span>
        </a>

        <a x-show="matchesMenu('executivo estratégia receita conversão indicadores')" href="{{ route('executive.index') }}" class="sb-nav-link {{ request()->routeIs('executive.*') ? 'active' : '' }}">
            <span class="sb-nav-icon">E</span><span class="sb-nav-label">Executivo</span>
        </a>
        <a x-show="matchesMenu('feed operações histórico atividades')" href="{{ route('operations.feed') }}"
           class="sb-nav-link {{ request()->routeIs('operations.*') ? 'active' : '' }}">
            <svg class="sb-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 6h16M4 12h16M4 18h10"/><circle cx="19" cy="18" r="2"/></svg>
            <span class="sb-hide-collapsed">Feed de Operações</span>
        </a>

        <div class="sb-nav-label sb-hide-collapsed">Operação</div>

        <div class="sb-nav-group" x-data="{ open: {{ request()->routeIs('crm.*') || request()->routeIs('crm.leads.*') ? 'true' : 'false' }} }"
             x-show="matchesMenu('comercial crm leads pipeline oportunidades')">
            <button type="button" class="sb-nav-link sb-nav-parent {{ request()->routeIs('crm.*') || request()->routeIs('crm.leads.*') ? 'active' : '' }}"
                    @click="collapsed ? window.location='{{ route('crm.index') }}' : open = !open">
                <svg class="sb-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 19V9M10 19V5M16 19v-7M22 19H2"/></svg>
                <span class="sb-hide-collapsed">Comercial</span>
                <svg class="sb-nav-chevron sb-hide-collapsed" :class="{'is-open': open}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
            </button>
            <div class="sb-nav-children sb-hide-collapsed" x-show="open" x-collapse>
                <a href="{{ route('crm.index') }}" class="sb-nav-child {{ request()->routeIs('crm.*') ? 'active' : '' }}">Pipeline CRM</a>
                <a href="{{ route('crm.leads.index') }}" class="sb-nav-child {{ request()->routeIs('crm.leads.*') ? 'active' : '' }}">Leads</a>
            </div>
        </div>

        <a x-show="matchesMenu('empresas clientes organizações')" href="{{ route('companies.index') }}"
           class="sb-nav-link {{ request()->routeIs('companies.*') ? 'active' : '' }}">
            <svg class="sb-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 21V5a2 2 0 0 1 2-2h8v18"/><path d="M14 9h4a2 2 0 0 1 2 2v10"/><path d="M8 7h2M8 11h2M8 15h2M17 13h1M17 17h1"/></svg>
            <span class="sb-hide-collapsed">Empresas</span>
        </a>

        @if(in_array(auth()->user()?->role, ['super_admin', 'admin_cto'], true))
            <a x-show="matchesMenu('usuários equipe acessos pessoas')" href="{{ route('users.index') }}"
               class="sb-nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <svg class="sb-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span class="sb-hide-collapsed">Usuários</span>
            </a>
        @endif

        <a x-show="matchesMenu('automação workflows regras gatilhos')" href="{{ route('automations.index') }}" class="sb-nav-link {{ request()->routeIs('automations.*') ? 'active' : '' }}">
            <svg class="sb-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2v5M12 17v5M4.9 4.9l3.5 3.5M15.6 15.6l3.5 3.5M2 12h5M17 12h5M4.9 19.1l3.5-3.5M15.6 8.4l3.5-3.5"/><circle cx="12" cy="12" r="3"/></svg>
            <span class="sb-hide-collapsed">Automações</span>
        </a>
        @if(in_array(auth()->user()?->role, ['super_admin', 'admin_cto'], true))
        <a x-show="matchesMenu('auditoria logs segurança histórico')" href="{{ route('audit.index') }}" class="sb-nav-link {{ request()->routeIs('audit.*') ? 'active' : '' }}">
            <svg class="sb-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3 4 6v6c0 5 3.4 8.6 8 10 4.6-1.4 8-5 8-10V6l-8-3Z"/><path d="m9 12 2 2 4-4"/></svg>
            <span class="sb-hide-collapsed">Auditoria</span>
        </a>
        @endif

        <div class="sb-nav-label sb-hide-collapsed">Ecossistema</div>

        @php
            $futureModules = [
                ['Marketing', 'campanhas anúncios conteúdo marketing', 'M12 20V10M18 20V4M6 20v-4'],
                ['SmartBot', 'smartbot atendimento whatsapp chat', 'M8 9h8M8 13h5M5 4h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H9l-5 3v-3H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2'],
                ['Agenda', 'agenda calendário compromissos', 'M7 3v3M17 3v3M4 9h16M5 5h14a1 1 0 0 1 1 1v14H4V6a1 1 0 0 1 1-1'],
                ['Contratos', 'contratos documentos assinaturas', 'M6 2h9l3 3v17H6zM14 2v4h4M9 11h6M9 15h6'],
            ];
        @endphp
        @foreach ($futureModules as [$label, $search, $path])
            <button x-show="matchesMenu('{{ $search }}')" type="button" class="sb-nav-link sb-nav-disabled" title="Módulo em preparação">
                <svg class="sb-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="{{ $path }}"/></svg>
                <span class="sb-hide-collapsed">{{ $label }}</span>
                <span class="sb-hide-collapsed ms-auto sb-badge sb-badge-neutral">Breve</span>
            </button>
        @endforeach

        @if(in_array(auth()->user()?->role, ['super_admin', 'admin_cto', 'finance', 'financeiro'], true))
            <button x-show="matchesMenu('financeiro receitas despesas cobranças')" type="button" class="sb-nav-link sb-nav-disabled" title="Módulo em preparação">
                <svg class="sb-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"/></svg>
                <span class="sb-hide-collapsed">Financeiro</span>
                <span class="sb-hide-collapsed ms-auto sb-badge sb-badge-neutral">Breve</span>
            </button>
        @endif

        <div class="sb-nav-empty sb-hide-collapsed" x-show="menuQuery && !hasMenuResults()" x-cloak>
            Nenhum módulo encontrado.
        </div>
    </nav>

    <div class="sb-sidebar-footer">
        <a href="{{ route('profile.edit') }}" class="sb-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <svg class="sb-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06-2.83 2.83-.06-.06A1.7 1.7 0 0 0 15 19.4a1.7 1.7 0 0 0-1 .6 1.7 1.7 0 0 0-.4 1.1V21h-4v-.09A1.7 1.7 0 0 0 8.6 19.4a1.7 1.7 0 0 0-1.88.34l-.06.06-2.83-2.83.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-.6-1 1.7 1.7 0 0 0-1.1-.4H3v-4h.09A1.7 1.7 0 0 0 4.6 8.6a1.7 1.7 0 0 0-.34-1.88l-.06-.06 2.83-2.83.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-.6 1.7 1.7 0 0 0 .4-1.1V3h4v.09A1.7 1.7 0 0 0 15.4 4.6a1.7 1.7 0 0 0 1.88-.34l.06-.06 2.83 2.83-.06.06A1.7 1.7 0 0 0 19.4 9c.16.36.48.66.86.84.25.12.52.18.8.18H21v4h-.09a1.7 1.7 0 0 0-1.51 1Z"/></svg>
            <span class="sb-hide-collapsed">Configurações</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sb-nav-link w-100 border-0 bg-transparent text-start">
                <svg class="sb-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 17l5-5-5-5M15 12H3M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/></svg>
                <span class="sb-hide-collapsed">Sair</span>
            </button>
        </form>
    </div>
</aside>
