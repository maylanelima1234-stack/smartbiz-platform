<header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8">

    <div>
        <h1 class="text-xl font-bold text-slate-900">
            SmartBiz Platform
        </h1>
        <p class="text-sm text-slate-500">
            Centro de operações da SmartBiz
        </p>
    </div>

    <div class="flex items-center gap-4">
        <span class="text-sm text-slate-500">
            {{ now()->format('d/m/Y') }}
        </span>

        <div class="flex items-center gap-3 bg-slate-100 px-4 py-2 rounded-xl">
            <div class="w-9 h-9 rounded-full bg-purple-600 text-white flex items-center justify-center font-bold">
                {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
            </div>

            <div class="text-sm">
                <strong class="block text-slate-900">
                    {{ auth()->user()->name ?? 'SmartBiz' }}
                </strong>
                <span class="text-slate-500">
                    {{ auth()->user()->email ?? '' }}
                </span>
            </div>
        </div>
    </div>

</header>