# SmartBiz 0.2.1-alpha — Kernel UX

## Antes de instalar

Faça uma cópia de segurança da pasta atual e do banco de dados.

## Instalação

1. Extraia este patch.
2. Copie todo o conteúdo para:

```text
C:\laragon\www\smartbiz-enterprise
```

3. Confirme a substituição dos arquivos.
4. Abra o terminal dentro do projeto e execute:

```bash
php artisan migrate
php artisan optimize:clear
```

Os arquivos front-end já estão compilados no patch. Caso faça alterações em CSS ou JavaScript depois, execute também:

```bash
npm install
npm run build
```

5. Se o servidor estiver parado:

```bash
php artisan serve
```

6. Atualize o navegador com `Ctrl + F5`.

## Testes

- Pressione `Ctrl + K`.
- Pesquise por uma empresa, lead ou usuário já cadastrado.
- Use as setas e `Enter` para navegar.
- Favorite um resultado pela estrela.
- Abra resultados recentes.
- Cadastre uma empresa, lead ou usuário.
- Abra o sino e confirme a nova notificação.
- Use “Marcar como lidas”.

## Importante

Esta versão cria a tabela `smart_notifications`. O comando `php artisan migrate` é obrigatório.
