# SmartBiz Enterprise 0.4.2 — Hotfix CRM/RBAC

## Objetivo

Esta build consolida as correções dos controllers do CRM para que as autorizações sejam tratadas pelas rotas e middlewares já existentes, evitando validações duplicadas no controller.

## Alterações aplicadas

- Removidas chamadas duplicadas de `SmartGate::authorize()` dos controllers do CRM.
- Removidas verificações duplicadas de licença dentro das páginas do CRM.
- Mantido o isolamento multiempresa por `PlatformContext` e pelas regras dos services.
- Preservadas as permissões das rotas por middleware.
- Padronizada a formatação dos controllers.

## Instalação

1. Faça backup do projeto e do banco de dados.
2. Substitua os arquivos do projeto pela nova build.
3. No terminal, dentro da pasta do projeto, execute:

```bash
composer install
php artisan optimize:clear
php artisan migrate
```

4. Inicie o servidor:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

## Validação recomendada

Teste com os perfis:

- administrador principal;
- administrador de tráfego;
- cliente gestor;
- vendedor.

Valide o acesso às páginas:

- CRM Dashboard;
- Leads;
- Lead 360°;
- Pipeline;
- Agenda;
- criação de atividade;
- comentários e tags.
