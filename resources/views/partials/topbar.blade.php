<header class="sb-topbar sb-topbar-premium">
    <button type="button" class="sb-icon-btn sb-mobile-only" @click="mobileOpen = true" aria-label="Abrir menu">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>
    <button type="button" class="sb-icon-btn sb-desktop-only" @click="collapsed = !collapsed" aria-label="Recolher menu">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" :class="{'sb-rotate-180': collapsed}"><path d="M15 18l-6-6 6-6"/></svg>
    </button>

    <div class="sb-topbar-heading">
        <div class="fw-bold">@yield('page_title', 'Centro de Operações')</div>
        <div class="small sb-muted">@yield('page_subtitle', 'SmartBiz Enterprise')</div>
    </div>

    <button type="button" class="sb-global-search sb-global-search-compact" @click="openCommand()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.3-3.3"/></svg>
        <span>Pesquisar</span><kbd>Ctrl K</kbd>
    </button>

    <div class="sb-topbar-spacer"></div>

    <div class="sb-dropdown" @click.outside="notificationsOpen = false">
        <button type="button" class="sb-icon-btn position-relative" @click="notificationsOpen = !notificationsOpen; profileOpen = false; if (notificationsOpen) loadNotifications()" aria-label="Notificações">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg>
            <span class="sb-notification-dot" x-show="unreadCount > 0" x-cloak></span>
            <span class="sb-notification-count" x-show="unreadCount > 0" x-text="unreadCount > 9 ? '9+' : unreadCount" x-cloak></span>
        </button>
        <div class="sb-popover sb-notifications" x-show="notificationsOpen" x-transition x-cloak>
            <div class="sb-popover-header"><div><strong>Notificações</strong><span x-text="unreadCount === 1 ? '1 pendente' : unreadCount + ' pendentes'"></span></div><button type="button" class="sb-link-button" x-show="unreadCount > 0" @click="markAllNotificationsRead()">Marcar como lidas</button></div>
            <div class="sb-notification-list">
                <div class="sb-notification-state" x-show="notificationsLoading">Carregando notificações...</div>
                <template x-for="notification in notifications" :key="notification.id"><button type="button" class="sb-notification-item sb-notification-button" :class="{'is-unread': !notification.read}" @click="openNotification(notification)"><span class="sb-notification-icon" x-text="notification.icon"></span><span class="sb-notification-copy"><strong x-text="notification.title"></strong><small x-text="notification.message"></small><em x-text="notification.created_at"></em></span></button></template>
                <div class="sb-notification-state" x-show="!notificationsLoading && notifications.length === 0"><strong>Tudo em dia</strong><span>Nenhuma notificação nova no momento.</span></div>
            </div>
        </div>
    </div>

    <div class="sb-dropdown" @click.outside="profileOpen = false">
        <button type="button" class="sb-profile sb-profile-compact" @click="profileOpen = !profileOpen; notificationsOpen = false" aria-label="Abrir menu do perfil">
            @php
                $authUser = auth()->user();
                $avatarPath = $authUser?->avatar;
                $avatarUrl = $avatarPath ? \Illuminate\Support\Facades\Storage::disk('public')->url($avatarPath) : null;
            @endphp
            <div class="sb-avatar sb-avatar-topbar">
                @if($avatarUrl)<img src="{{ $avatarUrl }}" alt="{{ $authUser?->name }}" onerror="this.remove(); this.parentElement.querySelector('span').style.display='grid'">@endif
                <span style="{{ $avatarUrl ? 'display:none' : '' }}">{{ strtoupper(substr($authUser?->name ?? 'U', 0, 1)) }}</span>
                <i class="sb-online-dot"></i>
            </div>
            <div class="sb-profile-copy"><div class="small fw-bold">{{ $authUser?->name ?? 'Usuário' }}</div><div class="small sb-muted">{{ $authUser?->roleLabel() ?? 'Administrador' }}</div></div>
            <svg class="sb-profile-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
        </button>

        <div class="sb-popover sb-profile-menu sb-profile-menu-premium" x-show="profileOpen" x-transition x-cloak>
            <div class="sb-profile-menu-head"><strong>{{ $authUser?->name ?? 'Usuário' }}</strong><span>{{ $authUser?->email ?? '' }}</span></div>
            <a href="{{ route('profile.edit') }}"><span>👤</span> Meu perfil</a>
            <a href="{{ route('users.index') }}"><span>👥</span> Gerenciar equipe</a>
            <div class="sb-popover-divider"></div>
            <div class="sb-theme-menu-title">Aparência</div>
            <div class="sb-theme-options">
                <button type="button" :class="{'is-active': themeMode === 'light'}" @click="setTheme('light')"><span>☀️</span> Claro <b x-show="themeMode === 'light'">✓</b></button>
                <button type="button" :class="{'is-active': themeMode === 'dark'}" @click="setTheme('dark')"><span>🌙</span> Escuro <b x-show="themeMode === 'dark'">✓</b></button>
                <button type="button" :class="{'is-active': themeMode === 'system'}" @click="setTheme('system')"><span>💻</span> Sistema <b x-show="themeMode === 'system'">✓</b></button>
            </div>
            <div class="sb-popover-divider"></div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="sb-logout-link"><span>↪</span> Sair da plataforma</button></form>
        </div>
    </div>
</header>
