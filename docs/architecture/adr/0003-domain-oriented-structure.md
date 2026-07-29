# ADR 0003 — Estrutura Orientada ao Domínio

- Status: Proposto
- Data: 2026-07-29
- Responsáveis: SmartBiz Engineering
- Relacionados:
  - ADR 0001 — Plataforma multi-interface e API-first
  - ADR 0002 — Monólito modular

---

## 1. Contexto

A SmartBiz Enterprise será utilizada por diferentes interfaces:

- painel web;
- API REST;
- aplicativo Android;
- aplicativo iOS;
- integrações externas;
- automações;
- jobs;
- comandos internos.

As regras de negócio não poderão ficar acopladas aos Controllers, às Views ou ao protocolo HTTP.

A estrutura deverá permitir que o mesmo caso de uso seja executado por diferentes interfaces sem duplicação.

Exemplo:

```text
Painel Web ──────┐
API REST ────────┤
Android ─────────┤
iOS ─────────────┼──→ CreateLeadAction
Integração ──────┤
Automação ───────┘