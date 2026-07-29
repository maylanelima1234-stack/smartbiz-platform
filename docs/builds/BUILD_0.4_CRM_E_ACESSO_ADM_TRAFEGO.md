# BUILD 0.4 — CRM estável e acesso Adm. Tráfego

## Entregas

- Relação multiempresa `User::companies()` corrigida.
- Cadastro e edição de usuários sincronizam automaticamente empresa, associação e perfil RBAC.
- Perfil `admin_tm` recebe CRM, agenda, empresas atribuídas, marketing e relatórios.
- Perfil `admin_tm` permanece sem Financeiro, Usuários globais, SmartBot e Marketplace.
- Timeline automática ao criar, editar e mover leads no Kanban.
- Responsáveis do CRM limitados aos usuários ativos da empresa selecionada.
- Rotas CRM protegidas com middleware de permissão.

## Criar acesso Adm. Tráfego

1. Entre como Adm. CTO.
2. Acesse **Usuários > Novo usuário**.
3. Selecione a empresa.
4. Em Perfil, escolha **Administrador Tráfego**.
5. Defina e-mail e senha.
6. Salve e teste o login em janela anônima.

## Segurança financeira

O perfil Adm. Tráfego não recebe nenhuma permissão `finance.*`. A ocultação do menu é acompanhada de bloqueio das rotas por RBAC.
