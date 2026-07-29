@php($user = auth()->user())

<nav x-data="{ open: false }" class="border-b border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <x-application-logo class="block h-9 w-auto fill-current text-slate-800" />
            </a>

            <div class="hidden items-center gap-3 sm:flex">
                <x-dropdown align="right" width="64">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-3 rounded-2xl border border-transparent p-1.5 pr-3 transition hover:border-slate-200 hover:bg-slate-50">
                            @if($user->avatar_url)
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-11 w-11 rounded-xl object-cover">
                            @else
                                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-600 to-violet-600 text-sm font-bold text-white">
                                    {{ $user->initials }}
                                </span>
                            @endif

                            <span class="text-left">
                                <span class="block max-w-40 truncate text-sm font-bold text-slate-800">{{ $user->name }}</span>
                                <span class="block max-w-40 truncate text-xs text-slate-500">{{ $user->position ?: $user->roleLabel() }}</span>
                            </span>

                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="border-b border-slate-100 px-4 py-3">
                            <p class="truncate text-sm font-bold text-slate-800">{{ $user->name }}</p>
                            <p class="truncate text-xs text-slate-500">{{ $user->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            Meu perfil
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                Sair
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <button @click="open = !open" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 sm:hidden">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/>
                    <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12M18 6 6 18"/>
                </svg>
            </button>
        </div>
    </div>

    <div x-show="open" x-cloak class="border-t border-slate-100 p-4 sm:hidden">
        <div class="flex items-center gap-3">
            @if($user->avatar_url)
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="h-12 w-12 rounded-xl object-cover">
            @else
                <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-600 to-violet-600 font-bold text-white">
                    {{ $user->initials }}
                </span>
            @endif
            <div class="min-w-0">
                <p class="truncate font-bold text-slate-800">{{ $user->name }}</p>
                <p class="truncate text-sm text-slate-500">{{ $user->email }}</p>
            </div>
        </div>

        <div class="mt-4 space-y-1">
            <a href="{{ route('profile.edit') }}" class="block rounded-xl px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Meu perfil
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full rounded-xl px-3 py-2 text-left text-sm font-semibold text-rose-600 hover:bg-rose-50">
                    Sair
                </button>
            </form>
        </div>
    </div>
</nav>
