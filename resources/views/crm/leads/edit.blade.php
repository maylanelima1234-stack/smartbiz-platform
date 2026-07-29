@extends('layouts.smartbiz')

@section('title', 'CRM — Editar lead')

@push('styles')
    @include('crm.partials.theme')
@endpush

@section('content')
<div class="crm-shell crm-page"><div class="crm-container max-w-5xl space-y-4"><div><div class="crm-muted text-xs font-bold uppercase tracking-[.16em]">CRM</div><h1 class="crm-title mt-1">Editar lead</h1><p class="crm-subtitle">Atualize os dados da oportunidade.</p></div>@include('crm.partials.nav')
<form method="POST" action="{{ route('crm.leads.update',$lead) }}" class="crm-card p-5">@csrf @method('PUT') @include('crm.leads._form')<div class="mt-5 flex justify-end gap-2"><a href="{{ route('crm.leads.show',$lead) }}" class="crm-btn">Cancelar</a><button class="crm-btn crm-btn-primary">Salvar alterações</button></div></form></div></div>
@endsection
