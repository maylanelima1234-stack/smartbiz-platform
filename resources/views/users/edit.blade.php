@extends('layouts.smartbiz')
@include('users.partials.theme')

@section('content')
<div class="team-shell">
    <div class="team-page-head"><div><h1>Editar usuário</h1><p>Atualize perfil, empresa, status e dados de acesso.</p></div><a href="{{ route('users.index') }}" class="team-btn team-btn-ghost">← Voltar</a></div>
    @if($errors->any())<div class="team-alert team-alert-danger"><strong>Revise os campos:</strong><ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <form class="team-card team-form-card" method="POST" action="{{ route('users.update',$user) }}" enctype="multipart/form-data">@csrf @method('PUT')
        <div class="team-section-title">Informações pessoais</div>
        <div class="team-form-grid">
            <div class="team-col-6"><label class="team-label">Nome *</label><input class="team-field" type="text" name="name" value="{{ old('name',$user->name) }}" required></div>
            <div class="team-col-6"><label class="team-label">E-mail *</label><input class="team-field" type="email" name="email" value="{{ old('email',$user->email) }}" required></div>
            <div class="team-col-4"><label class="team-label">Telefone</label><input class="team-field" type="text" name="phone" value="{{ old('phone',$user->phone) }}"></div>
            <div class="team-col-4"><label class="team-label">Cargo</label><input class="team-field" type="text" name="position" value="{{ old('position',$user->position) }}"></div>
            <div class="team-col-4"><label class="team-label">Foto do usuário</label><div class="team-avatar-upload"><div class="team-avatar-preview" id="avatarPreview">@if($user->avatar_url)<img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" onerror="this.remove();this.parentElement.textContent='{{ mb_strtoupper(mb_substr($user->name,0,1)) }}'">@else{{ mb_strtoupper(mb_substr($user->name,0,1)) }}@endif</div><div class="team-file-wrap"><input class="team-field" type="file" name="avatar" id="avatarInput" accept="image/jpeg,image/png,image/webp"><label class="team-help"><input type="checkbox" name="remove_avatar" value="1"> Remover foto atual</label></div></div></div>
        </div>
        <div class="team-section-title mt-4">Empresa e acesso</div>
        <div class="team-form-grid">
            <div class="team-col-4"><label class="team-label">Empresa</label><select class="team-select" name="company_id"><option value="">SmartBiz / Sem empresa</option>@foreach($companies ?? [] as $company)<option value="{{ $company->id }}" @selected(old('company_id',$user->company_id)==$company->id)>{{ $company->trade_name ?: $company->name }}</option>@endforeach</select></div>
            <div class="team-col-4"><label class="team-label">Perfil *</label><select class="team-select" name="role" required>@foreach(['admin'=>'Administrador','admin_cto'=>'Administrador CTO','admin_tm'=>'Administrador Tráfego','gestor'=>'Gestor','cliente_gestor'=>'Gestor da Empresa','comercial'=>'Comercial','financeiro'=>'Financeiro','vendedor'=>'Vendedor','cliente'=>'Cliente'] as $value=>$label)<option value="{{ $value }}" @selected(old('role',$user->role)===$value)>{{ $label }}</option>@endforeach</select></div>
            <div class="team-col-4"><label class="team-label">Status *</label><select class="team-select" name="status" required><option value="Ativo" @selected(old('status',$user->status)==='Ativo')>Ativo</option><option value="Bloqueado" @selected(old('status',$user->status)==='Bloqueado')>Bloqueado</option></select></div>
        </div>
        <div class="team-section-title mt-4">Alterar senha</div>
        <div class="team-form-grid"><div class="team-col-6"><label class="team-label">Nova senha</label><input class="team-field" type="password" name="password" placeholder="Deixe vazio para manter a senha atual"></div><div class="team-col-6"><label class="team-label">Confirmar nova senha</label><input class="team-field" type="password" name="password_confirmation"></div></div>
        <div class="team-form-footer"><a href="{{ route('users.show',$user) }}" class="team-btn team-btn-ghost">Cancelar</a><button class="team-btn team-btn-primary" type="submit">Salvar alterações</button></div>
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
