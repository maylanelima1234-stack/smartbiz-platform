@extends('layouts.smartbiz')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4"><div><h1 class="fw-bold">Editar Lead</h1><p class="text-muted-sb">Atualize a oportunidade comercial.</p></div><a href="{{ route('leads.index') }}" class="btn btn-outline-light">Voltar</a></div>
@if($errors->any())<div class="alert alert-danger"><strong>Revise os campos.</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<div class="sb-card"><form method="POST" action="{{ route('leads.update', $lead) }}">@method('PUT') @include('leads._form')</form></div>
@endsection
