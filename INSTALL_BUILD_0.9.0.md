# SmartBiz Enterprise 0.9.0 — Core CRM & Executive

## Instalação

1. Faça backup do projeto e do banco.
2. Preserve o arquivo `.env` e a pasta `storage/app`.
3. Substitua os arquivos pela BUILD 0.9.0.
4. Execute:

```bash
composer install
php artisan optimize:clear
php artisan migrate
npm install
npm run build
php artisan serve --host=0.0.0.0 --port=8000
```

## Correção principal

O cadastro de leads agora usa valores padronizados entre interface e backend. `Indicação` é armazenado como `indicacao`, e a prioridade `Média` como `normal`. Valores antigos como `medium` também são normalizados automaticamente.

## Novidades

- Enums para origem, prioridade e status de leads.
- Mensagens de validação visíveis no formulário.
- Filtro de etapas conforme o pipeline escolhido.
- Dashboard Executivo em `/executive`.
- Histórico de execuções das automações em `/automations/runs`.
- Indicadores de receita, pipeline, ticket médio, conversão, crescimento e desempenho por responsável/origem.
