# Instalação — BUILD 0.4.1

1. Preserve seu arquivo `.env`.
2. Substitua os arquivos pelo projeto atualizado.
3. Execute:

```bash
composer dump-autoload
php artisan optimize:clear
php artisan migrate
php artisan db:seed --class=AuthorizationSeeder
php artisan optimize:clear
php artisan serve
```

## Validação

- Entre com `daiana.adm@smartbusinessm.com`.
- O Dashboard deve abrir sem vínculo obrigatório com uma empresa cliente.
- Use o seletor **Empresa ativa** para alternar entre as empresas.
- Confirme acesso ao CRM e Leads.
- Confirme que Usuários globais e Financeiro permanecem indisponíveis.
