<section>
    <header class="mb-4"><h2 class="sb-section-title">Dados pessoais</h2><p class="sb-muted mb-0">Atualize seu nome e endereço de e-mail.</p></header>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>
    <form method="post" action="{{ route('profile.update') }}" class="sb-form-grid">@csrf @method('patch')
        <div class="sb-field"><label for="name">Nome</label><input id="name" name="name" type="text" value="{{ old('name',$user->name) }}" required autofocus autocomplete="name">@error('name')<small class="sb-field-error">{{ $message }}</small>@enderror</div>
        <div class="sb-field"><label for="email">E-mail</label><input id="email" name="email" type="email" value="{{ old('email',$user->email) }}" required autocomplete="username">@error('email')<small class="sb-field-error">{{ $message }}</small>@enderror</div>
        @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())<div class="sb-inline-note">Seu e-mail ainda não foi verificado. <button form="send-verification" type="submit">Reenviar verificação</button></div>@endif
        <div class="d-flex align-items-center gap-3"><button class="sb-btn sb-btn-primary" type="submit">Salvar alterações</button>@if(session('status')==='profile-updated')<span class="sb-success-text" x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,2500)">Salvo com sucesso.</span>@endif</div>
    </form>
</section>
