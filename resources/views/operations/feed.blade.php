@extends('layouts.smartbiz')

@section('title', 'Feed de Operações | SmartBiz')
@section('page_title', 'Feed de Operações')
@section('page_subtitle', 'Histórico unificado da empresa')

@section('content')
<x-smart.page-header title="Feed de Operações" subtitle="Acompanhe as principais movimentações do CRM em ordem cronológica">
    <x-slot:actions>
        <x-smart.button variant="secondary" :href="route('dashboard')">Voltar ao Centro de Operações</x-smart.button>
    </x-slot:actions>
</x-smart.page-header>

<x-smart.card>
    <form method="GET" class="row g-3 align-items-end mb-4">
        <div class="col-lg-7">
            <label class="form-label fw-semibold">Pesquisar</label>
            <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Lead, título ou descrição...">
        </div>
        <div class="col-lg-3">
            <label class="form-label fw-semibold">Tipo</label>
            <select name="type" class="form-select">
                <option value="">Todos os eventos</option>
                @foreach($types as $type)
                    <option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2 d-grid">
            <button class="btn btn-primary">Filtrar</button>
        </div>
    </form>

    <div class="sb-operation-timeline">
        @forelse($events as $event)
            <article class="sb-operation-event">
                <div class="sb-operation-dot"></div>
                <div class="sb-operation-card">
                    <div class="d-flex flex-wrap justify-content-between gap-2">
                        <div>
                            <div class="fw-bold">{{ $event->title }}</div>
                            <div class="small sb-muted mt-1">{{ $event->description ?: 'Movimentação registrada no sistema.' }}</div>
                        </div>
                        <time class="small sb-muted">{{ $event->created_at?->format('d/m/Y H:i') }}</time>
                    </div>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <x-smart.badge variant="neutral">{{ ucfirst(str_replace('_', ' ', $event->type)) }}</x-smart.badge>
                        @if($event->lead)
                            <a class="small fw-semibold text-decoration-none" href="{{ route('crm.leads.show', $event->lead) }}">{{ $event->lead->name }}</a>
                        @endif
                        @if($event->user)<span class="small sb-muted">por {{ $event->user->name }}</span>@endif
                    </div>
                </div>
            </article>
        @empty
            <x-smart.empty-state title="Nenhuma movimentação encontrada" description="Os eventos do CRM aparecerão aqui automaticamente." />
        @endforelse
    </div>

    <div class="mt-4">{{ $events->links() }}</div>
</x-smart.card>
@endsection
