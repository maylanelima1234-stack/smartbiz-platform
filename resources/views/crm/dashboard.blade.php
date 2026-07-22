<x-app-layout>
<x-slot name="header">
<div class="flex flex-wrap items-center justify-between gap-3">
<div><h2 class="text-xl font-semibold text-gray-800">CRM Executivo</h2><p class="text-sm text-gray-500">Indicadores comerciais da empresa</p></div>
<div class="flex gap-2"><a href="{{ route('crm.agenda') }}" class="rounded-lg border px-4 py-2 text-sm">Agenda</a><a href="{{ route('crm.leads.index') }}" class="rounded-lg border px-4 py-2 text-sm">Leads</a><a href="{{ route('crm.kanban') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm text-white">Kanban</a></div>
</div>
</x-slot>
<div class="py-8"><div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
@foreach([['Leads',$total],['Em andamento',$open],['Conversão',number_format($conversion_rate,1,',','.').'%'],['Follow-ups vencidos',$overdue_count]] as $card)
<div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200"><p class="text-sm text-gray-500">{{ $card[0] }}</p><p class="mt-2 text-3xl font-bold text-gray-900">{{ $card[1] }}</p></div>
@endforeach
</div>
<div class="grid gap-4 md:grid-cols-3">
<div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200"><p class="text-sm text-gray-500">Receita ganha</p><p class="mt-2 text-2xl font-bold text-emerald-600">R$ {{ number_format($won_value,2,',','.') }}</p></div>
<div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200"><p class="text-sm text-gray-500">Pipeline aberto</p><p class="mt-2 text-2xl font-bold text-indigo-600">R$ {{ number_format($open_value,2,',','.') }}</p></div>
<div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200"><p class="text-sm text-gray-500">Ticket médio</p><p class="mt-2 text-2xl font-bold">R$ {{ number_format($average_ticket,2,',','.') }}</p></div>
</div>
<div class="grid gap-6 lg:grid-cols-2">
<div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200"><div class="border-b p-5"><h3 class="font-semibold">Origem dos leads</h3></div><div class="divide-y">@forelse($source_stats as $item)<div class="flex justify-between p-4"><span>{{ $item->source }}</span><strong>{{ $item->total }}</strong></div>@empty<p class="p-5 text-sm text-gray-500">Sem dados.</p>@endforelse</div></div>
<div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200"><div class="border-b p-5"><h3 class="font-semibold">Ranking comercial</h3></div><div class="divide-y">@forelse($seller_ranking as $seller)<div class="flex justify-between p-4"><div><p class="font-medium">{{ $seller->name }}</p><p class="text-xs text-gray-500">{{ $seller->deals }} vendas</p></div><strong class="text-emerald-600">R$ {{ number_format((float)$seller->revenue,2,',','.') }}</strong></div>@empty<p class="p-5 text-sm text-gray-500">Sem vendas atribuídas.</p>@endforelse</div></div>
</div>
<div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-200"><div class="border-b p-5"><h3 class="font-semibold">Leads recentes</h3></div><div class="divide-y">@forelse($recent_leads as $lead)<a href="{{ route('crm.leads.show',$lead) }}" class="flex justify-between p-5 hover:bg-gray-50"><div><p class="font-medium">{{ $lead->name }}</p><p class="text-sm text-gray-500">{{ $lead->stage?->name ?? 'Sem etapa' }} · {{ $lead->owner?->name ?? 'Não atribuído' }}</p></div><p class="font-medium">R$ {{ number_format((float)$lead->value,2,',','.') }}</p></a>@empty<p class="p-6 text-sm text-gray-500">Nenhum lead cadastrado.</p>@endforelse</div></div>
</div></div>
</x-app-layout>
