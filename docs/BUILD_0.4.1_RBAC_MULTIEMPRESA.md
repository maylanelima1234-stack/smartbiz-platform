# BUILD 0.4.1 — RBAC Multiempresa

## Regra aplicada

- `admin_cto` e `super_admin`: escopo global.
- `admin_tm`: perfil interno SmartBiz com escopo multiempresa operacional.
- usuários clientes: continuam dependentes de vínculo ativo na tabela `company_user`.

## Adm. Tráfego

O perfil `admin_tm` não depende mais de `users.company_id` para entrar no Dashboard.
Ele pode alternar entre empresas cadastradas e o CRM passa a usar a empresa ativa da sessão.
As permissões continuam vindo do papel global `admin_tm`, sem liberar Financeiro, Usuários globais ou Configurações administrativas.

## Atualização

```bash
php artisan migrate
php artisan db:seed --class=AuthorizationSeeder
php artisan optimize:clear
```
