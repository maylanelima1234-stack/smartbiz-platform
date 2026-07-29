<nav class="crm-nav" aria-label="Navegação do CRM">
    <a href="{{ route('crm.dashboard') }}" class="{{ request()->routeIs('crm.dashboard','crm.index') ? 'active' : '' }}">Visão geral</a>
    <a href="{{ route('crm.leads.index') }}" class="{{ request()->routeIs('crm.leads.*') ? 'active' : '' }}">Leads</a>
    <a href="{{ route('crm.kanban') }}" class="{{ request()->routeIs('crm.kanban*') ? 'active' : '' }}">Pipeline</a>
    <a href="{{ route('crm.agenda') }}" class="{{ request()->routeIs('crm.agenda') ? 'active' : '' }}">Agenda</a>
</nav>
