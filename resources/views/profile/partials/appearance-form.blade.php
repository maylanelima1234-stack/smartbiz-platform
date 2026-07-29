<section>
    <header class="mb-4"><h2 class="sb-section-title">Aparência</h2><p class="sb-muted mb-0">Escolha como o SmartBiz deve aparecer.</p></header>
    <div class="sb-appearance-grid">
        <button type="button" @click="setTheme('light')" :class="{'is-active':themeMode==='light'}"><span class="sb-theme-preview is-light"></span><strong>Claro</strong><small>Visual iluminado</small></button>
        <button type="button" @click="setTheme('dark')" :class="{'is-active':themeMode==='dark'}"><span class="sb-theme-preview is-dark"></span><strong>Escuro</strong><small>Mais confortável</small></button>
        <button type="button" @click="setTheme('system')" :class="{'is-active':themeMode==='system'}"><span class="sb-theme-preview is-system"></span><strong>Sistema</strong><small>Segue o dispositivo</small></button>
    </div>
</section>
