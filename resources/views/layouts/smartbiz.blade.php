<!DOCTYPE html>
<html lang="pt-BR" x-data="smartBizShell()" :data-theme="theme">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SmartBiz Enterprise')</title>
    <script>
        (function () {
            const savedTheme = localStorage.getItem('smartbiz-theme');
            const preferred = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            document.documentElement.dataset.theme = savedTheme || preferred;
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak]{display:none!important}</style>
    @stack('styles')
</head>
<body class="smartbiz-body">
<div class="sb-shell" :class="{'is-collapsed': collapsed, 'mobile-open': mobileOpen}">
    @include('partials.sidebar')
    <div class="sb-mobile-overlay" @click="mobileOpen = false"></div>

    <div class="sb-main">
        @include('partials.topbar')
        <main class="sb-content">
            @if (session('success'))
                <div class="alert alert-success border-0 rounded-4 shadow-sm">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger border-0 rounded-4 shadow-sm">{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

@include('partials.command-palette')

<script>
    function smartBizShell() {
        return {
            collapsed: localStorage.getItem('smartbiz-sidebar') === 'collapsed',
            mobileOpen: false,
            theme: localStorage.getItem('smartbiz-theme') || document.documentElement.dataset.theme || 'light',
            menuQuery: '',
            commandOpen: false,
            commandQuery: '',
            commandLoading: false,
            remoteResults: [],
            selectedIndex: 0,
            searchTimer: null,
            notificationsOpen: false,
            profileOpen: false,
            notificationsLoading: false,
            notifications: [],
            unreadCount: 0,
            recentItems: JSON.parse(localStorage.getItem('smartbiz-recent') || '[]'),
            favoriteItems: JSON.parse(localStorage.getItem('smartbiz-favorites') || '[]'),
            commands: [
                { id: 'menu-dashboard', category: 'Navegação', label: 'Dashboard', description: 'Visão geral da operação', initial: 'D', url: @json(route('dashboard')), keywords: 'início indicadores visão geral' },
                { id: 'menu-crm', category: 'Navegação', label: 'CRM', description: 'Pipeline e oportunidades', initial: 'C', url: @json(route('crm.index')), keywords: 'comercial pipeline oportunidades' },
                { id: 'menu-leads', category: 'Navegação', label: 'Leads', description: 'Gerenciar leads cadastrados', initial: 'L', url: @json(route('leads.index')), keywords: 'contatos oportunidades comercial' },
                { id: 'menu-companies', category: 'Navegação', label: 'Empresas', description: 'Clientes e organizações', initial: 'E', url: @json(route('companies.index')), keywords: 'clientes contas organizações' },
                { id: 'menu-users', category: 'Navegação', label: 'Usuários', description: 'Equipe e acessos', initial: 'U', url: @json(route('users.index')), keywords: 'equipe pessoas permissões' },
                { id: 'menu-profile', category: 'Navegação', label: 'Meu perfil', description: 'Preferências da conta', initial: 'P', url: @json(route('profile.edit')), keywords: 'configurações conta senha' },
            ],
            quickActions: [
                { id: 'action-company', category: 'Ações rápidas', label: 'Nova empresa', description: 'Cadastrar uma nova organização', initial: '+', url: @json(route('companies.create')), keywords: 'criar cadastrar empresa cliente' },
                { id: 'action-lead', category: 'Ações rápidas', label: 'Novo lead', description: 'Adicionar oportunidade ao CRM', initial: '+', url: @json(route('leads.create')), keywords: 'criar cadastrar lead oportunidade' },
                { id: 'action-user', category: 'Ações rápidas', label: 'Novo usuário', description: 'Adicionar membro à equipe', initial: '+', url: @json(route('users.create')), keywords: 'criar cadastrar usuário equipe' },
            ],
            init() {
                this.$watch('collapsed', value => localStorage.setItem('smartbiz-sidebar', value ? 'collapsed' : 'expanded'));
                this.$watch('theme', value => {
                    localStorage.setItem('smartbiz-theme', value);
                    document.documentElement.dataset.theme = value;
                });
                this.$watch('commandQuery', () => {
                    this.selectedIndex = 0;
                    clearTimeout(this.searchTimer);
                    this.searchTimer = setTimeout(() => this.fetchGlobalSearch(), 240);
                });
                window.addEventListener('keydown', event => {
                    if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
                        event.preventDefault();
                        this.openCommand();
                    }
                    if (event.key === 'Escape') this.closeCommand();
                });
                this.loadNotifications();
            },
            csrf() {
                return document.querySelector('meta[name="csrf-token"]')?.content || '';
            },
            toggleTheme() { this.theme = this.theme === 'dark' ? 'light' : 'dark'; },
            matchesMenu(terms) {
                return !this.menuQuery.trim() || terms.toLowerCase().includes(this.menuQuery.trim().toLowerCase());
            },
            hasMenuResults() {
                const terms = 'dashboard visão geral início comercial crm leads pipeline oportunidades empresas clientes organizações usuários equipe acessos pessoas marketing campanhas anúncios conteúdo smartbot atendimento whatsapp chat financeiro receitas despesas cobranças agenda calendário compromissos contratos documentos assinaturas';
                return terms.includes(this.menuQuery.trim().toLowerCase());
            },
            openCommand() {
                this.commandOpen = true;
                this.notificationsOpen = false;
                this.profileOpen = false;
                this.selectedIndex = 0;
                this.$nextTick(() => this.$refs.commandInput?.focus());
            },
            closeCommand() {
                this.commandOpen = false;
                this.commandQuery = '';
                this.remoteResults = [];
                this.selectedIndex = 0;
            },
            localResults() {
                const query = this.commandQuery.trim().toLowerCase();
                const source = [...this.quickActions, ...this.commands];
                if (!query) return source;
                return source.filter(item => `${item.label} ${item.description} ${item.keywords}`.toLowerCase().includes(query));
            },
            allCommandResults() {
                const query = this.commandQuery.trim();
                const local = this.localResults();
                if (query.length < 2) return local;
                const seen = new Set(local.map(item => item.url));
                return [...local, ...this.remoteResults.filter(item => !seen.has(item.url))];
            },
            displayRecent() {
                return !this.commandQuery.trim() ? this.recentItems.slice(0, 4) : [];
            },
            displayFavorites() {
                return !this.commandQuery.trim() ? this.favoriteItems.slice(0, 4) : [];
            },
            async fetchGlobalSearch() {
                const query = this.commandQuery.trim();
                if (query.length < 2) {
                    this.remoteResults = [];
                    this.commandLoading = false;
                    return;
                }
                this.commandLoading = true;
                try {
                    const response = await fetch(@json(route('global-search')) + '?q=' + encodeURIComponent(query), {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (!response.ok) throw new Error('Falha na pesquisa');
                    const data = await response.json();
                    this.remoteResults = data.results || [];
                } catch (error) {
                    this.remoteResults = [];
                } finally {
                    this.commandLoading = false;
                }
            },
            moveSelection(direction) {
                const total = this.allCommandResults().length;
                if (!total) return;
                this.selectedIndex = (this.selectedIndex + direction + total) % total;
                this.$nextTick(() => document.querySelector('.sb-command-item.is-selected')?.scrollIntoView({ block: 'nearest' }));
            },
            openSelected() {
                const item = this.allCommandResults()[this.selectedIndex];
                if (item) this.navigateTo(item);
            },
            navigateTo(item) {
                const entry = { id: item.id || item.url, label: item.label, description: item.description, initial: item.initial, url: item.url, category: item.category || 'Recentes' };
                const recent = [entry, ...this.recentItems.filter(existing => existing.url !== entry.url)].slice(0, 8);
                this.recentItems = recent;
                localStorage.setItem('smartbiz-recent', JSON.stringify(recent));
                window.location.href = item.url;
            },
            isFavorite(item) {
                return this.favoriteItems.some(existing => existing.url === item.url);
            },
            toggleFavorite(item, event) {
                event?.preventDefault();
                event?.stopPropagation();
                if (this.isFavorite(item)) {
                    this.favoriteItems = this.favoriteItems.filter(existing => existing.url !== item.url);
                } else {
                    this.favoriteItems = [{ id: item.id || item.url, label: item.label, description: item.description, initial: item.initial, url: item.url, category: item.category || 'Favoritos' }, ...this.favoriteItems].slice(0, 8);
                }
                localStorage.setItem('smartbiz-favorites', JSON.stringify(this.favoriteItems));
            },
            async loadNotifications() {
                this.notificationsLoading = true;
                try {
                    const response = await fetch(@json(route('notifications.index')), { headers: { 'Accept': 'application/json' } });
                    if (!response.ok) throw new Error('Falha ao carregar notificações');
                    const data = await response.json();
                    this.notifications = data.notifications || [];
                    this.unreadCount = data.unread_count || 0;
                } catch (error) {
                    this.notifications = [];
                    this.unreadCount = 0;
                } finally {
                    this.notificationsLoading = false;
                }
            },
            async openNotification(notification) {
                if (!notification.read) {
                    await fetch(@json(url('/notifications')) + '/' + notification.id + '/read', {
                        method: 'PATCH',
                        headers: { 'X-CSRF-TOKEN': this.csrf(), 'Accept': 'application/json' }
                    });
                    notification.read = true;
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                }
                window.location.href = notification.url;
            },
            async markAllNotificationsRead() {
                await fetch(@json(route('notifications.read-all')), {
                    method: 'PATCH',
                    headers: { 'X-CSRF-TOKEN': this.csrf(), 'Accept': 'application/json' }
                });
                this.notifications = this.notifications.map(item => ({ ...item, read: true }));
                this.unreadCount = 0;
            },
        }
    }
</script>
@stack('scripts')
</body>
</html>
