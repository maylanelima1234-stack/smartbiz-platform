# SmartBiz 0.2.0-alpha

## Aplicação

Esta atualização é um patch visual e não altera o banco de dados.

1. Faça uma cópia de segurança do projeto atual.
2. Copie o conteúdo do patch para a raiz de `smartbiz-enterprise`, substituindo os arquivos indicados.
3. Execute:

```bash
npm install
npm run build
php artisan optimize:clear
php artisan serve
```

4. Atualize o navegador com `Ctrl + F5`.

## Testes rápidos

- Abra e recolha a sidebar.
- Pesquise por `lead` no menu lateral.
- Use `Ctrl + K` e navegue para Empresas.
- Abra notificações e menu de perfil.
- Troque entre tema claro e escuro.
