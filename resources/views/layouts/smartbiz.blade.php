<!DOCTYPE html>
<html lang="pt-BR" x-data="smartBizShell()" :data-theme="resolvedTheme">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SmartBiz Enterprise')</title>
    <script>
        (() => {
            const mode = localStorage.getItem('smartbiz-theme-mode') || localStorage.getItem('smartbiz-theme') || 'system';
            const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const resolved = mode === 'system' ? (systemDark ? 'dark' : 'light') : mode;
            document.documentElement.dataset.theme = resolved;
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
            themeMode: localStorage.getItem('smartbiz-theme-mode') || localStorage.getItem('smartbiz-theme') || 'system',
            resolvedTheme: document.documentElement.dataset.theme || 'dark',
            systemThemeMedia: null,
            menuQuery: '', commandOpen: false, commandQuery: '', commandLoading: false,
            remoteResults: [], selectedIndex: 0, searchTimer: null,
            notificationsOpen: false, profileOpen: false, notificationsLoading: false,
            notifications: [], unreadCount: 0,
            recentItems: JSON.parse(localStorage.getItem('smartbiz-recent') || '[]'),
            favoriteItems: JSON.parse(localStorage.getItem('smartbiz-favorites') || '[]'),
            commands: [
                { id:'menu-dashboard', category:'Navegação', label:'Dashboard', description:'Visão geral da operação', initial:'D', url:@json(route('dashboard')), keywords:'início indicadores visão geral' },
                { id:'menu-executive', category:'Navegação', label:'Dashboard Executivo', description:'Receita, conversão e performance', initial:'E', url:@json(route('executive.index')), keywords:'estratégia receita ticket conversão crescimento' },
                { id:'menu-automations', category:'Navegação', label:'Automações', description:'Regras e workflows do CRM', initial:'A', url:@json(route('automations.index')), keywords:'workflow gatilho condição ação' },
                { id:'menu-crm', category:'Navegação', label:'CRM', description:'Pipeline e oportunidades', initial:'C', url:@json(route('crm.index')), keywords:'comercial pipeline oportunidades' },
                { id:'menu-leads', category:'Navegação', label:'Leads', description:'Gerenciar leads cadastrados', initial:'L', url:@json(route('crm.leads.index')), keywords:'contatos oportunidades comercial' },
                { id:'menu-companies', category:'Navegação', label:'Empresas', description:'Clientes e organizações', initial:'E', url:@json(route('companies.index')), keywords:'clientes contas organizações' },
                { id:'menu-users', category:'Navegação', label:'Usuários', description:'Equipe e acessos', initial:'U', url:@json(route('users.index')), keywords:'equipe pessoas permissões' },
                { id:'menu-profile', category:'Navegação', label:'Meu perfil', description:'Preferências da conta', initial:'P', url:@json(route('profile.edit')), keywords:'configurações conta senha aparência' },
            ],
            quickActions: [
                { id:'action-company', category:'Ações rápidas', label:'Nova empresa', description:'Cadastrar uma nova organização', initial:'+', url:@json(route('companies.create')), keywords:'criar cadastrar empresa cliente' },
                { id:'action-lead', category:'Ações rápidas', label:'Novo lead', description:'Adicionar oportunidade ao CRM', initial:'+', url:@json(route('crm.leads.create')), keywords:'criar cadastrar lead oportunidade' },
                { id:'action-user', category:'Ações rápidas', label:'Novo usuário', description:'Adicionar membro à equipe', initial:'+', url:@json(route('users.create')), keywords:'criar cadastrar usuário equipe' },
            ],
            init() {
                this.systemThemeMedia = window.matchMedia('(prefers-color-scheme: dark)');
                this.applyTheme();
                this.systemThemeMedia.addEventListener('change', () => { if (this.themeMode === 'system') this.applyTheme(); });
                this.$watch('collapsed', value => localStorage.setItem('smartbiz-sidebar', value ? 'collapsed' : 'expanded'));
                this.$watch('themeMode', () => this.applyTheme());
                this.$watch('commandQuery', () => { this.selectedIndex = 0; clearTimeout(this.searchTimer); this.searchTimer = setTimeout(() => this.fetchGlobalSearch(), 240); });
                window.addEventListener('keydown', event => {
                    if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') { event.preventDefault(); this.openCommand(); }
                    if (event.key === 'Escape') { this.closeCommand(); this.profileOpen = false; this.notificationsOpen = false; }
                });
                this.loadNotifications();
            },
            applyTheme() {
                localStorage.setItem('smartbiz-theme-mode', this.themeMode);
                localStorage.removeItem('smartbiz-theme');
                this.resolvedTheme = this.themeMode === 'system' ? (this.systemThemeMedia?.matches ? 'dark' : 'light') : this.themeMode;
                document.documentElement.dataset.theme = this.resolvedTheme;
            },
            setTheme(mode) { this.themeMode = mode; },
            csrf() { return document.querySelector('meta[name="csrf-token"]')?.content || ''; },
            matchesMenu(terms) { return !this.menuQuery.trim() || terms.toLowerCase().includes(this.menuQuery.trim().toLowerCase()); },
            hasMenuResults() { const terms='dashboard visão geral início comercial crm leads pipeline oportunidades empresas clientes organizações usuários equipe acessos pessoas marketing campanhas anúncios conteúdo smartbot atendimento whatsapp chat financeiro receitas despesas cobranças agenda calendário compromissos contratos documentos assinaturas'; return terms.includes(this.menuQuery.trim().toLowerCase()); },
            openCommand() { this.commandOpen=true; this.notificationsOpen=false; this.profileOpen=false; this.selectedIndex=0; this.$nextTick(() => this.$refs.commandInput?.focus()); },
            closeCommand() { this.commandOpen=false; this.commandQuery=''; this.remoteResults=[]; this.selectedIndex=0; },
            localResults() { const q=this.commandQuery.trim().toLowerCase(); const source=[...this.quickActions,...this.commands]; return q ? source.filter(i => `${i.label} ${i.description} ${i.keywords}`.toLowerCase().includes(q)) : source; },
            allCommandResults() { const local=this.localResults(); if(this.commandQuery.trim().length<2) return local; const seen=new Set(local.map(i=>i.url)); return [...local,...this.remoteResults.filter(i=>!seen.has(i.url))]; },
            displayRecent() { return !this.commandQuery.trim() ? this.recentItems.slice(0,4) : []; },
            displayFavorites() { return !this.commandQuery.trim() ? this.favoriteItems.slice(0,4) : []; },
            async fetchGlobalSearch() { const q=this.commandQuery.trim(); if(q.length<2){this.remoteResults=[];this.commandLoading=false;return;} this.commandLoading=true; try{const r=await fetch(@json(route('global-search'))+'?q='+encodeURIComponent(q),{headers:{Accept:'application/json'}}); if(!r.ok) throw new Error(); const d=await r.json(); this.remoteResults=d.results||[];}catch(e){this.remoteResults=[];}finally{this.commandLoading=false;} },
            moveSelection(direction) { const total=this.allCommandResults().length; if(!total)return; this.selectedIndex=(this.selectedIndex+direction+total)%total; this.$nextTick(()=>document.querySelector('.sb-command-item.is-selected')?.scrollIntoView({block:'nearest'})); },
            openSelected() { const item=this.allCommandResults()[this.selectedIndex]; if(item)this.navigateTo(item); },
            navigateTo(item) { const entry={id:item.id||item.url,label:item.label,description:item.description,initial:item.initial,url:item.url,category:item.category||'Recentes'}; this.recentItems=[entry,...this.recentItems.filter(e=>e.url!==entry.url)].slice(0,8); localStorage.setItem('smartbiz-recent',JSON.stringify(this.recentItems)); window.location.href=item.url; },
            isFavorite(item) { return this.favoriteItems.some(e=>e.url===item.url); },
            toggleFavorite(item,event) { event?.preventDefault();event?.stopPropagation(); this.favoriteItems=this.isFavorite(item)?this.favoriteItems.filter(e=>e.url!==item.url):[{id:item.id||item.url,label:item.label,description:item.description,initial:item.initial,url:item.url,category:item.category||'Favoritos'},...this.favoriteItems].slice(0,8); localStorage.setItem('smartbiz-favorites',JSON.stringify(this.favoriteItems)); },
            async loadNotifications() { this.notificationsLoading=true; try{const r=await fetch(@json(route('notifications.index')),{headers:{Accept:'application/json'}});if(!r.ok)throw new Error();const d=await r.json();this.notifications=d.notifications||[];this.unreadCount=d.unread_count||0;}catch(e){this.notifications=[];this.unreadCount=0;}finally{this.notificationsLoading=false;} },
            async openNotification(notification) { if(!notification.read){await fetch(@json(url('/notifications'))+'/'+notification.id+'/read',{method:'PATCH',headers:{'X-CSRF-TOKEN':this.csrf(),Accept:'application/json'}});notification.read=true;this.unreadCount=Math.max(0,this.unreadCount-1);}window.location.href=notification.url; },
            async markAllNotificationsRead() { await fetch(@json(route('notifications.read-all')),{method:'PATCH',headers:{'X-CSRF-TOKEN':this.csrf(),Accept:'application/json'}});this.notifications=this.notifications.map(i=>({...i,read:true}));this.unreadCount=0; },
        }
    }
</script>
@stack('scripts')
</body>
</html>
