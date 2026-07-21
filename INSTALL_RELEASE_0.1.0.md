# Instalação — Release 0.1.0-alpha

1. Faça backup do projeto atual.
2. Substitua os arquivos desta release no projeto local.
3. No terminal, dentro da pasta do projeto, execute:

```bash
npm install
npm run build
php artisan optimize:clear
php artisan serve
```

4. Acesse `http://127.0.0.1:8000`.

## Validação

- O dashboard deve abrir com contagens reais.
- O botão lateral deve recolher a sidebar no desktop.
- O menu deve abrir sobre a tela no celular.
- O botão de tema deve alternar entre claro e escuro.
- Empresas, Usuários e CRM devem continuar acessíveis.
