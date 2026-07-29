# ADR-007 — Adm. Tráfego sem acesso financeiro inicial

## Status
Aceito em 27/07/2026.

## Decisão
O perfil `admin_tm` não recebe permissões financeiras, de usuários globais, SmartBot administrativo ou Marketplace por padrão.

O acesso financeiro poderá ser concedido posteriormente pelo Adm. CTO por permissão individual e com auditoria.

## Controles obrigatórios

1. O item Financeiro não aparece no menu sem autorização.
2. Rotas financeiras usam o middleware `permission`.
3. Controllers e serviços sensíveis devem executar autorização novamente.
4. Digitar a URL diretamente não contorna a proteção.
5. Toda concessão futura deve registrar concedente, motivo, início e expiração.

## Perfil operacional inicial
O `admin_tm` recebe acesso a Dashboard, Empresas da carteira, CRM, Marketing, Atendimentos, Agenda, Relatórios e Suporte.
