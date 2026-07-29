# SmartBiz Enterprise 0.8.0-alpha

## Instalação

1. Preserve o arquivo `.env` da instalação atual.
2. Substitua os arquivos pelo conteúdo desta build.
3. Execute:

```bash
composer install
php artisan optimize:clear
php artisan migrate
npm install
npm run build
php artisan serve --host=0.0.0.0 --port=8000
```

## Novos acessos

- `/automations`
- `/audit`

## Teste recomendado

Crie uma automação com gatilho **Lead criado**, ação **Criar notificação** e depois cadastre um novo lead.
