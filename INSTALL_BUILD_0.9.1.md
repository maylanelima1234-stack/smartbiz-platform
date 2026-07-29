# SmartBiz Enterprise 0.9.1 — Professional Panels

Atualização visual focada nos módulos de Automações e Auditoria.

## Instalação
1. Preserve o arquivo `.env`.
2. Substitua os arquivos do projeto.
3. Execute:

```powershell
composer install
php artisan optimize:clear
npm install
npm run build
php artisan serve --host=0.0.0.0 --port=8000
```

Esta atualização não cria novas tabelas e não exige migration.
