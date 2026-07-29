# SmartBiz Enterprise 0.5.0-alpha — Lead 360°

## Instalação
1. Faça backup do projeto e banco.
2. Substitua os arquivos pelo conteúdo deste pacote.
3. Execute:

```bash
composer install
php artisan optimize:clear
php artisan migrate
php artisan storage:link
php artisan serve --host=0.0.0.0 --port=8000
```

## Teste rápido
- Abra CRM > Leads.
- Acesse um lead.
- Registre uma atividade e um comentário.
- Envie um arquivo de até 10 MB.
- Edite ou mova o lead e confirme os eventos na timeline.

## Observação
Os arquivos são armazenados no disco `local`, isolados por empresa e lead.
