@extends('layouts.smartbiz')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold">Editar Empresa</h1>
        <p class="text-muted-sb">Atualize os dados da empresa.</p>
    </div>

    <a href="{{ route('companies.index') }}" class="btn btn-outline-light">Voltar</a>
</div>

<div class="sb-card">
    <form action="{{ route('companies.update', $company) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('companies._form', ['company' => $company, 'buttonText' => 'Salvar Alterações'])
    </form>
</div>

@endsection
