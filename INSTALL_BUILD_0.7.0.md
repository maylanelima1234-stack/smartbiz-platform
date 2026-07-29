# SmartBiz Enterprise 0.7.0-alpha — Enterprise Workspace

## Entrega

- Centro de Operações com dados reais do CRM.
- Painel automático de prioridades: atividades vencidas e leads parados.
- Agenda resumida para hoje, vencidas e próximos 7 dias.
- Gráfico real de entrada de leads nos últimos 7 dias.
- Conversão, valor do pipeline e vendas concluídas.
- Feed inteligente no dashboard.
- Página completa `/operations/feed`, com busca, filtro e paginação.
- Novo acesso “Feed de Operações” na sidebar.
- Isolamento por empresa ativa preservado.
- Nenhuma migration nova é necessária nesta build.

## Instalação

1. Faça backup do projeto e do banco.
2. Substitua os arquivos pela pasta desta build.
3. Preserve seu arquivo `.env`.
4. Execute:

```bash
composer install
php artisan optimize:clear
php artisan migrate
npm install
npm run build
php artisan serve --host=0.0.0.0 --port=8000
```

## Testes rápidos

- `/dashboard`
- `/operations/feed`
- `/crm/dashboard`
- `/crm/kanban`
- `/crm/agenda`

A tela continuará funcionando mesmo sem eventos na timeline ou sem atividades cadastradas; nesses casos, serão exibidos estados vazios.
