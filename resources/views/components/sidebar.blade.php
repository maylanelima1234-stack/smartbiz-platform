<aside class="w-72 min-h-screen bg-[#13061F] border-r border-purple-900/30 text-white hidden md:flex md:flex-col">

    <div class="px-6 py-6 border-b border-purple-900/30">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-purple-600 flex items-center justify-center font-bold">
                SB
            </div>

            <div>
                <strong class="block text-lg">SmartBiz</strong>
                <span class="text-xs text-purple-200">Platform Enterprise</span>
            </div>
        </div>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2">
        <a href="/dashboard" class="block px-4 py-3 rounded-xl bg-purple-600 text-white font-medium">
            🏠 Dashboard
        </a>

        <a href="{{ route('companies.index') }}" class="menu-link">
            🏢 Empresas
        </a>

        <a href="#" class="block px-4 py-3 rounded-xl text-purple-100 hover:bg-purple-900/40">
            👥 Usuários
        </a>

        <a href="#" class="block px-4 py-3 rounded-xl text-purple-100 hover:bg-purple-900/40">
            📈 CRM
        </a>

        <a href="#" class="block px-4 py-3 rounded-xl text-purple-100 hover:bg-purple-900/40">
            💰 Financeiro
        </a>

        <a href="#" class="block px-4 py-3 rounded-xl text-purple-100 hover:bg-purple-900/40">
            📊 Performance
        </a>

        <a href="#" class="block px-4 py-3 rounded-xl text-purple-100 hover:bg-purple-900/40">
            🤖 SmartBiz AI
        </a>

        <a href="#" class="block px-4 py-3 rounded-xl text-purple-100 hover:bg-purple-900/40">
            ⚙️ Sistema
        </a>
    </nav>

    <div class="px-4 py-6 border-t border-purple-900/30">
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="w-full text-left px-4 py-3 rounded-xl text-red-300 hover:bg-red-900/20">
                🚪 Sair
            </button>
        </form>
    </div>

</aside>