@extends('layouts.smartbiz')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4"><div><h1 class="fw-bold">Novo Lead</h1><p class="text-muted-sb">Cadastre uma nova oportunidade comercial.</p></div><a href="{{ route('crm.leads.index') }}" class="btn btn-outline-light">Voltar</a></div>
@if($errors->any())<div class="alert alert-danger"><strong>Revise os campos.</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<div class="sb-card"><form method="POST" action="{{ route('crm.leads.store') }}" onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerText='Salvando...';">@include('leads._form')</form></div>
@endsection
