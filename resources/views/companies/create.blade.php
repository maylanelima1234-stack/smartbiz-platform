@extends('layouts.smartbiz')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold">Nova Empresa</h1>
        <p class="text-muted-sb">Cadastre uma nova empresa cliente da SmartBiz.</p>
    </div>

    <a href="{{ route('companies.index') }}" class="btn btn-outline-light">Voltar</a>
</div>

<div class="sb-card">
    <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('companies._form', ['buttonText' => 'Salvar Empresa'])
    </form>
</div>

@endsection
