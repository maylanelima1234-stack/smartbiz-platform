@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Revise os campos abaixo:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-4">
    <div class="col-12">
        <h5 class="fw-bold mb-0">Dados da empresa</h5>
        <p class="text-muted-sb mb-0">Informações principais do cliente.</p>
    </div>

    <div class="col-md-6">
        <label class="form-label">Razão social *</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $company->name ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Nome fantasia</label>
        <input type="text" name="trade_name" class="form-control" value="{{ old('trade_name', $company->trade_name ?? '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">CNPJ</label>
        <input type="text" name="cnpj" class="form-control js-cnpj" value="{{ old('cnpj', $company->cnpj ?? '') }}" placeholder="00.000.000/0000-00">
    </div>

    <div class="col-md-4">
        <label class="form-label">Responsável</label>
        <input type="text" name="owner" class="form-control" value="{{ old('owner', $company->owner ?? '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Site</label>
        <input type="text" name="website" class="form-control" value="{{ old('website', $company->website ?? '') }}" placeholder="https://">
    </div>

    <div class="col-md-6">
        <label class="form-label">E-mail</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $company->email ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Telefone / WhatsApp</label>
        <input type="text" name="phone" class="form-control js-phone" value="{{ old('phone', $company->phone ?? '') }}" placeholder="(00) 00000-0000">
    </div>

    <div class="col-12 mt-3">
        <h5 class="fw-bold mb-0">Endereço</h5>
        <p class="text-muted-sb mb-0">Dados úteis para contrato, financeiro e atendimento.</p>
    </div>

    <div class="col-md-3">
        <label class="form-label">CEP</label>
        <input type="text" name="zip_code" id="zip_code" class="form-control js-cep" value="{{ old('zip_code', $company->zip_code ?? '') }}" placeholder="00000-000">
    </div>

    <div class="col-md-6">
        <label class="form-label">Endereço</label>
        <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $company->address ?? '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">Número</label>
        <input type="text" name="number" class="form-control" value="{{ old('number', $company->number ?? '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Complemento</label>
        <input type="text" name="complement" class="form-control" value="{{ old('complement', $company->complement ?? '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">Bairro</label>
        <input type="text" name="district" id="district" class="form-control" value="{{ old('district', $company->district ?? '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label">Cidade</label>
        <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $company->city ?? '') }}">
    </div>

    <div class="col-md-1">
        <label class="form-label">UF</label>
        <input type="text" name="state" id="state" class="form-control text-uppercase" value="{{ old('state', $company->state ?? '') }}" maxlength="2">
    </div>

    <div class="col-12 mt-3">
        <h5 class="fw-bold mb-0">Plano e operação</h5>
        <p class="text-muted-sb mb-0">Configurações iniciais do contrato.</p>
    </div>

    <div class="col-md-4">
        <label class="form-label">Plano</label>
        <select name="plan" class="form-select">
            @foreach(['Starter','Performance','Enterprise'] as $plan)
                <option value="{{ $plan }}" @selected(old('plan', $company->plan ?? 'Starter') === $plan)>{{ $plan }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            @foreach(['Ativa','Suspensa','Cancelada'] as $status)
                <option value="{{ $status }}" @selected(old('status', $company->status ?? 'Ativa') === $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Logo</label>
        <input type="file" name="logo" class="form-control" accept="image/png,image/jpeg,image/webp">
    </div>

    <div class="col-12">
        <label class="form-label">Observações</label>
        <textarea name="notes" rows="4" class="form-control">{{ old('notes', $company->notes ?? '') }}</textarea>
    </div>
</div>

<div class="mt-4 d-flex justify-content-end gap-2">
    <a href="{{ route('companies.index') }}" class="btn btn-outline-light">Cancelar</a>
    <button type="submit" class="btn btn-primary px-5 js-submit-btn">
        {{ $buttonText ?? 'Salvar Empresa' }}
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form').forEach(function(form){
        form.addEventListener('submit', function(){
            const btn = form.querySelector('.js-submit-btn');
            if(btn){
                btn.disabled = true;
                btn.innerText = 'Salvando...';
            }
        });
    });

    const onlyNumbers = (value) => value.replace(/\D/g, '');

    document.querySelectorAll('.js-cnpj').forEach(function(input){
        input.addEventListener('input', function(){
            let v = onlyNumbers(input.value).slice(0,14);
            v = v.replace(/^(\d{2})(\d)/, '$1.$2');
            v = v.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
            v = v.replace(/\.(\d{3})(\d)/, '.$1/$2');
            v = v.replace(/(\d{4})(\d)/, '$1-$2');
            input.value = v;
        });
    });

    document.querySelectorAll('.js-phone').forEach(function(input){
        input.addEventListener('input', function(){
            let v = onlyNumbers(input.value).slice(0,11);
            if (v.length > 10) {
                v = v.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
            } else {
                v = v.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
            }
            input.value = v;
        });
    });

    const cep = document.getElementById('zip_code');
    if(cep){
        cep.addEventListener('input', function(){
            let v = onlyNumbers(cep.value).slice(0,8);
            v = v.replace(/^(\d{5})(\d)/, '$1-$2');
            cep.value = v;
        });

        cep.addEventListener('blur', async function(){
            const raw = onlyNumbers(cep.value);
            if(raw.length !== 8) return;

            try {
                const response = await fetch(`https://viacep.com.br/ws/${raw}/json/`);
                const data = await response.json();
                if(data.erro) return;

                document.getElementById('address').value = data.logradouro || '';
                document.getElementById('district').value = data.bairro || '';
                document.getElementById('city').value = data.localidade || '';
                document.getElementById('state').value = data.uf || '';
            } catch (e) {}
        });
    }
});
</script>
