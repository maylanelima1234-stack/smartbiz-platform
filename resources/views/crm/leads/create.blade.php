@extends('layouts.smartbiz')

@section('title', 'CRM — Novo lead')

@push('styles')
    @include('crm.partials.theme')
@endpush

@section('content')
<div class="crm-shell crm-page"><div class="crm-container max-w-5xl space-y-4"><div><div class="crm-muted text-xs font-bold uppercase tracking-[.16em]">CRM</div><h1 class="crm-title mt-1">Novo lead</h1><p class="crm-subtitle">Cadastre uma nova oportunidade comercial.</p></div>@include('crm.partials.nav')
<form method="POST" action="{{ route('crm.leads.store') }}" class="crm-card p-5">@csrf @include('crm.leads._form')<div class="mt-5 flex justify-end gap-2"><a href="{{ route('crm.leads.index') }}" class="crm-btn">Cancelar</a><button class="crm-btn crm-btn-primary">Salvar lead</button></div></form></div></div>
@endsection
