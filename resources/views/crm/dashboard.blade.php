<x-app-layout>
    <x-slot name="header">
        <x-smart.page-header
            eyebrow="Smart CRM"
            title="Dashboard comercial"
            subtitle="Acompanhe seu funil, receita e oportunidades em uma visão executiva."
        >
            <x-slot:actions>
                <a href="{{ route('crm.leads.index') }}"
                   class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    Ver leads
                </a>
                <a href="{{ route('crm.kanban') }}"
                   class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                    Abrir Kanban
                </a>
            </x-slot:actions>
        </x-smart.page-header>
    </x-slot>

    @php
        $closed = $won + $lost;
        $openRate = $total > 0 ? round(($open / $total) * 100, 1) : 0;
        $wonRate = $closed > 0 ? round(($won / $closed) * 100, 1) : 0;
        $lostRate = $closed > 0 ? round(($lost / $closed) * 100, 1) : 0;
        $averageTicket = $won > 0 ? $won_value / $won : 0;
    @endphp

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <x-smart.stat-card
                    label="Leads no funil"
                    :value="number_format($total, 0, ',', '.')"
                    caption="Total de oportunidades cadastradas"
                    tone="indigo"
                    icon='<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>'
                />

                <x-smart.stat-card
                    label="Em andamento"
                    :value="number_format($open, 0, ',', '.')"
                    :caption="number_format($openRate, 1, ',', '.') . '% do funil permanece aberto'"
                    tone="amber"
                    icon='<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/></svg>'
                />

                <x-smart.stat-card
                    label="Receita ganha"
                    :value="'R$ ' . number_format($won_value, 2, ',', '.')"
                    :caption="'Ticket médio de R$ ' . number_format($averageTicket, 2, ',', '.')"
                    tone="emerald"
                    icon='<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"/></svg>'
                />

                <x-smart.stat-card
                    label="Conversão geral"
                    :value="number_format($conversion_rate, 1, ',', '.') . '%'"
                    :caption="number_format($won, 0, ',', '.') . ' oportunidades ganhas'"
                    tone="slate"
                    icon='<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4 13 4 4L20 5"/></svg>'
                />
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <x-smart.panel
                    title="Saúde do funil"
                    subtitle="Distribuição atual das oportunidades"
                    class="lg:col-span-2"
                >
                    <div class="grid gap-6 sm:grid-cols-3">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Abertos</p>
                            <p class="mt-1 text-2xl font-bold text-slate-950">{{ $open }}</p>
                            <x-smart.progress class="mt-4" :value="$openRate" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500">Ganhos</p>
                            <p class="mt-1 text-2xl font-bold text-emerald-600">{{ $won }}</p>
                            <x-smart.progress class="mt-4" :value="$wonRate" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500">Perdidos</p>
                            <p class="mt-1 text-2xl font-bold text-rose-600">{{ $lost }}</p>
                            <x-smart.progress class="mt-4" :value="$lostRate" />
                        </div>
                    </div>

                    <div class="mt-7 rounded-xl bg-slate-50 p-4 ring-1 ring-slate-100">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="font-semibold text-slate-900">Desempenho comercial</p>
                                <p class="text-sm text-slate-500">Conversão calculada sobre todos os leads cadastrados.</p>
                            </div>
                            <span class="rounded-full bg-white px-3 py-1 text-sm font-bold text-indigo-700 ring-1 ring-slate-200">
                                {{ number_format($conversion_rate, 1, ',', '.') }}%
                            </span>
                        </div>
                    </div>
                </x-smart.panel>

                <x-smart.panel title="Acesso rápido" subtitle="Rotinas mais usadas">
                    <div class="space-y-3">
                        <a href="{{ route('crm.kanban') }}" class="group flex items-center justify-between rounded-xl border border-slate-200 p-4 transition hover:border-indigo-200 hover:bg-indigo-50/50">
                            <div>
                                <p class="font-semibold text-slate-900">Gerenciar pipeline</p>
                                <p class="text-sm text-slate-500">Mover oportunidades entre etapas</p>
                            </div>
                            <span class="text-xl text-slate-300 transition group-hover:translate-x-1 group-hover:text-indigo-600">→</span>
                        </a>

                        <a href="{{ route('crm.leads.index') }}" class="group flex items-center justify-between rounded-xl border border-slate-200 p-4 transition hover:border-indigo-200 hover:bg-indigo-50/50">
                            <div>
                                <p class="font-semibold text-slate-900">Consultar leads</p>
                                <p class="text-sm text-slate-500">Pesquisar e revisar oportunidades</p>
                            </div>
                            <span class="text-xl text-slate-300 transition group-hover:translate-x-1 group-hover:text-indigo-600">→</span>
                        </a>
                    </div>
                </x-smart.panel>
            </div>

            <x-smart.panel title="Leads recentes" subtitle="Últimas oportunidades adicionadas" :padded="false">
                <x-slot:actions>
                    <a href="{{ route('crm.leads.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">Ver todos</a>
                </x-slot:actions>

                @forelse ($recent_leads as $lead)
                    <a href="{{ route('crm.leads.show', $lead) }}"
                       class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 transition last:border-b-0 hover:bg-slate-50 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex min-w-0 items-center gap-3">
                            <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-indigo-50 font-bold text-indigo-700">
                                {{ strtoupper(mb_substr($lead->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-slate-900">{{ $lead->name }}</p>
                                <p class="truncate text-sm text-slate-500">
                                    {{ $lead->stage?->name ?? 'Sem etapa' }} · {{ $lead->owner?->name ?? 'Não atribuído' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-4 sm:justify-end">
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                {{ ucfirst($lead->status) }}
                            </span>
                            <p class="whitespace-nowrap font-bold text-slate-900">R$ {{ number_format((float) $lead->value, 2, ',', '.') }}</p>
                        </div>
                    </a>
                @empty
                    <x-smart.empty-state
                        title="Nenhum lead cadastrado"
                        description="Cadastre oportunidades para começar a acompanhar seu funil comercial."
                    />
                @endforelse
            </x-smart.panel>
        </div>
    </div>
</x-app-layout>
