<div class="sb-command-backdrop" x-show="commandOpen" x-transition.opacity x-cloak @click.self="closeCommand()">
    <section class="sb-command" role="dialog" aria-modal="true" aria-label="Pesquisa global">
        <div class="sb-command-input-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.3-3.3"/></svg>
            <input x-ref="commandInput" x-model="commandQuery"
                   @keydown.escape="closeCommand()"
                   @keydown.arrow-down.prevent="moveSelection(1)"
                   @keydown.arrow-up.prevent="moveSelection(-1)"
                   @keydown.enter.prevent="openSelected()"
                   type="search" placeholder="Pesquise empresas, leads, usuários ou ações...">
            <span class="sb-command-spinner" x-show="commandLoading" aria-label="Pesquisando"></span>
            <kbd>ESC</kbd>
        </div>

        <div class="sb-command-body">
            <template x-if="displayFavorites().length">
                <div>
                    <div class="sb-command-label">Favoritos</div>
                    <template x-for="item in displayFavorites()" :key="'favorite-' + item.url">
                        <button type="button" class="sb-command-item sb-command-item-button" @click="navigateTo(item)">
                            <span class="sb-command-item-icon" x-text="item.initial"></span>
                            <span><strong x-text="item.label"></strong><small x-text="item.description"></small></span>
                            <span class="sb-command-favorite is-active">★</span>
                        </button>
                    </template>
                </div>
            </template>

            <template x-if="displayRecent().length">
                <div>
                    <div class="sb-command-label">Acessados recentemente</div>
                    <template x-for="item in displayRecent()" :key="'recent-' + item.url">
                        <button type="button" class="sb-command-item sb-command-item-button" @click="navigateTo(item)">
                            <span class="sb-command-item-icon" x-text="item.initial"></span>
                            <span><strong x-text="item.label"></strong><small x-text="item.description"></small></span>
                            <span class="sb-command-arrow">→</span>
                        </button>
                    </template>
                </div>
            </template>

            <div class="sb-command-label" x-text="commandQuery.trim() ? 'Resultados' : 'Navegação e ações rápidas'"></div>
            <template x-for="(item, index) in allCommandResults()" :key="item.id || item.url">
                <div role="button" tabindex="0" class="sb-command-item sb-command-item-button"
                        :class="{'is-selected': selectedIndex === index}"
                        @mouseenter="selectedIndex = index"
                        @click="navigateTo(item)"
                        @keydown.enter.prevent="navigateTo(item)">
                    <span class="sb-command-item-icon" x-text="item.initial"></span>
                    <span class="sb-command-item-copy">
                        <strong x-text="item.label"></strong>
                        <small><span x-text="item.category"></span> · <span x-text="item.description"></span></small>
                    </span>
                    <button type="button" class="sb-command-favorite" :class="{'is-active': isFavorite(item)}"
                            @click="toggleFavorite(item, $event)" :aria-label="isFavorite(item) ? 'Remover dos favoritos' : 'Adicionar aos favoritos'">
                        <span x-text="isFavorite(item) ? '★' : '☆'"></span>
                    </button>
                </div>
            </template>

            <div class="sb-command-empty" x-show="!commandLoading && allCommandResults().length === 0">
                Nenhum resultado para “<span x-text="commandQuery"></span>”.
            </div>
        </div>
        <footer class="sb-command-footer">
            <span><kbd>↑</kbd><kbd>↓</kbd> navegar</span>
            <span><kbd>Enter</kbd> abrir</span>
            <span><kbd>☆</kbd> favoritar</span>
        </footer>
    </section>
</div>
