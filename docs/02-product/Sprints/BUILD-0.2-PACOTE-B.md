# BUILD 0.2 — Pacote B

## Entrega

- Dashboard separado por perfil.
- Workspace operacional por empresa.
- Health Score inicial sem dados financeiros.
- Equipe e leads recentes no workspace.
- Alternância segura da empresa ativa.
- Rotas de Empresas e Usuários protegidas por ação.

## Regra financeira

O perfil `admin_tm` continua sem qualquer visualização financeira. O workspace não apresenta receita, cobranças, mensalidades, planos comerciais detalhados ou custos. A liberação futura será feita por permissões granulares.

## Homologação

1. Entrar como Adm. CTO.
2. Abrir Empresas e acessar “Workspace”.
3. Validar indicadores, equipe e leads recentes.
4. Entrar como Adm. Tráfego.
5. Confirmar que o dashboard é operacional e que Financeiro não aparece.
6. Tentar acessar uma rota administrativa de usuários e validar o retorno 403.
