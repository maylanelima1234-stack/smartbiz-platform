# SmartBiz Enterprise 0.6.0-alpha — CRM Velocity

## Instalação

1. Faça backup do projeto e do banco.
2. Substitua os arquivos pelo conteúdo desta build.
3. Execute:

```bash
composer install
php artisan optimize:clear
php artisan migrate
php artisan storage:link
php artisan serve --host=0.0.0.0 --port=8000
```

## Testes rápidos

- Abra `/crm/dashboard` e confira o Centro de Atenção.
- Abra `/crm/kanban`, aplique filtros e mova um lead entre etapas.
- Force uma falha de rede e confirme que o card volta à etapa anterior.
