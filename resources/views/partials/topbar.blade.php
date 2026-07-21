<header class="sb-topbar">
    <button type="button" class="sb-icon-btn sb-mobile-only" @click="mobileOpen = true" aria-label="Abrir menu">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>
    <button type="button" class="sb-icon-btn sb-desktop-only" @click="collapsed = !collapsed" aria-label="Recolher menu">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" :class="{'sb-rotate-180': collapsed}"><path d="M15 18l-6-6 6-6"/></svg>
    </button>

    <div class="sb-topbar-heading">
        <div class="fw-bold">@yield('page_title', 'Centro de Operações')</div>
        <div class="small sb-muted">@yield('page_subtitle', 'SmartBiz Enterprise')</div>
    </div>

    <button type="button" class="sb-global-search" @click="openCommand()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.3-3.3"/></svg>
        <span>Pesquisar no SmartBiz</span>
        <kbd>Ctrl K</kbd>
    </button>

    <div class="sb-topbar-spacer"></div>

    <button type="button" class="sb-icon-btn" @click="toggleTheme()" aria-label="Alternar tema">
        <svg x-show="theme === 'light'" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3v2M12 19v2M3 12h2M19 12h2M5.64 5.64l1.42 1.42M16.94 16.94l1.42 1.42M18.36 5.64l-1.42 1.42M7.06 16.94l-1.42 1.42"/><circle cx="12" cy="12" r="4"/></svg>
        <svg x-show="theme === 'dark'" x-cloak width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.5 14.2A8.5 8.5 0 0 1 9.8 3.5 8.5 8.5 0 1 0 20.5 14.2Z"/></svg>
    </button>

    <div class="sb-dropdown" @click.outside="notificationsOpen = false">
        <button type="button" class="sb-icon-btn position-relative" @click="notificationsOpen = !notificationsOpen; if (notificationsOpen) loadNotifications()" aria-label="Notificações">
            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg>
            <span class="sb-notification-dot" x-show="unreadCount > 0" x-cloak></span>
            <span class="sb-notification-count" x-show="unreadCount > 0" x-text="unreadCount > 9 ? '9+' : unreadCount" x-cloak></span>
        </button>
        <div class="sb-popover sb-notifications" x-show="notificationsOpen" x-transition x-cloak>
            <div class="sb-popover-header">
                <div><strong>Notificações</strong><span x-text="unreadCount === 1 ? '1 pendente' : unreadCount + ' pendentes'"></span></div>
                <button type="button" class="sb-link-button" x-show="unreadCount > 0" @click="markAllNotificationsRead()">Marcar como lidas</button>
            </div>
            <div class="sb-notification-list">
                <div class="sb-notification-state" x-show="notificationsLoading">Carregando notificações...</div>
                <template x-for="notification in notifications" :key="notification.id">
                    <button type="button" class="sb-notification-item sb-notification-button" :class="{'is-unread': !notification.read}" @click="openNotification(notification)">
                        <span class="sb-notification-icon" x-text="notification.icon"></span>
                        <span class="sb-notification-copy">
                            <strong x-text="notification.title"></strong>
                            <small x-text="notification.message"></small>
                            <em x-text="notification.created_at"></em>
                        </span>
                    </button>
                </template>
                <div class="sb-notification-state" x-show="!notificationsLoading && notifications.length === 0">
                    <strong>Tudo em dia</strong>
                    <span>Nenhuma notificação nova no momento.</span>
                </div>
            </div>
        </div>
    </div>

    <div class="sb-dropdown" @click.outside="profileOpen = false">
        <button type="button" class="sb-profile" @click="profileOpen = !profileOpen" aria-label="Abrir menu do perfil">
            <div class="sb-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
            <div class="sb-profile-copy">
                <div class="small fw-bold">{{ auth()->user()->name ?? 'Usuário' }}</div>
                <div class="small sb-muted">{{ auth()->user()->roleLabel() ?? 'Administrador' }}</div>
            </div>
            <svg class="sb-profile-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div class="sb-popover sb-profile-menu" x-show="profileOpen" x-transition x-cloak>
            <div class="sb-profile-menu-head">
                <strong>{{ auth()->user()->name ?? 'Usuário' }}</strong>
                <span>{{ auth()->user()->email ?? '' }}</span>
            </div>
            <a href="{{ route('profile.edit') }}">Meu perfil</a>
            <a href="{{ route('users.index') }}">Gerenciar equipe</a>
            <button type="button" @click="profileOpen = false; openCommand()">Pesquisa global</button>
            <div class="sb-popover-divider"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Sair da plataforma</button>
            </form>
        </div>
    </div>
</header>
