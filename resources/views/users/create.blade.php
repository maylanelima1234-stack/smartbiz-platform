@extends('layouts.smartbiz')
@include('users.partials.theme')

@section('content')
<div class="team-shell">
    <div class="team-page-head"><div><h1>Novo usuário</h1><p>Crie um acesso e vincule-o à empresa e ao perfil correto.</p></div><a href="{{ route('users.index') }}" class="team-btn team-btn-ghost">← Voltar</a></div>
    @if($errors->any())<div class="team-alert team-alert-danger"><strong>Revise os campos:</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <form class="team-card team-form-card" method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data" onsubmit="const b=this.querySelector('[type=submit]');b.disabled=true;b.innerText='Salvando...';">@csrf
        <div class="team-section-title">Informações pessoais</div>
        <div class="team-form-grid">
            <div class="team-col-6"><label class="team-label">Nome *</label><input class="team-field" type="text" name="name" value="{{ old('name') }}" required></div>
            <div class="team-col-6"><label class="team-label">E-mail *</label><input class="team-field" type="email" name="email" value="{{ old('email') }}" required></div>
            <div class="team-col-4"><label class="team-label">Telefone</label><input class="team-field" type="text" name="phone" value="{{ old('phone') }}" placeholder="(00) 00000-0000"></div>
            <div class="team-col-4"><label class="team-label">Cargo</label><input class="team-field" type="text" name="position" value="{{ old('position') }}" placeholder="Ex.: Gestor comercial"></div>
            <div class="team-col-4"><label class="team-label">Foto</label><div class="team-avatar-upload"><div class="team-avatar-preview" id="avatarPreview">U</div><div class="team-file-wrap"><input class="team-field" type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/webp"><div class="team-help">JPG, PNG ou WEBP, até 2 MB.</div></div></div></div>
        </div>

        <div class="team-section-title mt-4">Empresa e acesso</div>
        <div class="team-form-grid">
            <div class="team-col-4"><label class="team-label">Empresa</label><select class="team-select" name="company_id"><option value="">SmartBiz / Sem empresa</option>@foreach($companies ?? [] as $company)<option value="{{ $company->id }}" @selected(old('company_id')==$company->id)>{{ $company->trade_name ?: $company->name }}</option>@endforeach</select></div>
            <div class="team-col-4"><label class="team-label">Perfil *</label><select class="team-select" name="role" required><option value="admin" @selected(old('role')==='admin')>Administrador</option><option value="admin_cto" @selected(old('role')==='admin_cto')>Administrador CTO</option><option value="admin_tm" @selected(old('role')==='admin_tm')>Administrador Tráfego</option><option value="gestor" @selected(old('role')==='gestor')>Gestor</option><option value="cliente_gestor" @selected(old('role')==='cliente_gestor')>Gestor da Empresa</option><option value="comercial" @selected(old('role')==='comercial')>Comercial</option><option value="financeiro" @selected(old('role')==='financeiro')>Financeiro</option><option value="vendedor" @selected(old('role')==='vendedor')>Vendedor</option><option value="cliente" @selected(old('role','cliente')==='cliente')>Cliente</option></select></div>
            <div class="team-col-4"><label class="team-label">Status *</label><select class="team-select" name="status" required><option value="Ativo" @selected(old('status','Ativo')==='Ativo')>Ativo</option><option value="Bloqueado" @selected(old('status')==='Bloqueado')>Bloqueado</option></select></div>
        </div>

        <div class="team-section-title mt-4">Segurança</div>
        <div class="team-form-grid"><div class="team-col-6"><label class="team-label">Senha *</label><input class="team-field" type="password" name="password" required></div><div class="team-col-6"><label class="team-label">Confirmar senha *</label><input class="team-field" type="password" name="password_confirmation" required></div></div>
        <div class="team-form-footer"><a href="{{ route('users.index') }}" class="team-btn team-btn-ghost">Cancelar</a><button class="team-btn team-btn-primary" type="submit">Salvar usuário</button></div>
    </form>
</div>
@push('scripts')
<script>
document.getElementById('avatarInput')?.addEventListener('change', function () {
    const file = this.files?.[0], preview = document.getElementById('avatarPreview');
    if (!file || !preview) return;
    const reader = new FileReader();
    reader.onload = e => preview.innerHTML = `<img src="${e.target.result}" alt="Prévia">`;
    reader.readAsDataURL(file);
});
</script>
@endpush
@endsection
