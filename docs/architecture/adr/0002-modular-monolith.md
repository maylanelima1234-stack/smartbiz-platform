# ADR 0002 — Monólito Modular

- Status: Proposto
- Data: 2026-07-29
- Responsáveis: SmartBiz Engineering
- Relacionado: ADR 0001 — Plataforma multi-interface e API-first

---

## 1. Contexto

A SmartBiz Enterprise está evoluindo de um sistema Laravel tradicional para
uma plataforma empresarial composta por vários módulos.

A plataforma deverá atender:

- painel web;
- API REST;
- aplicativo Android;
- aplicativo iOS;
- automações;
- integrações externas;
- processos em fila;
- webhooks.

Também deverá comportar diferentes áreas de negócio:

- CRM;
- Marketing;
- Financeiro;
- Agenda;
- Atendimento;
- Relatórios;
- Documentos;
- Inteligência Artificial.

Manter toda a aplicação organizada apenas nas pastas tradicionais do Laravel,
como `Controllers`, `Models` e `Services`, poderá gerar:

- concentração excessiva de responsabilidades;
- classes muito grandes;
- dependências desorganizadas;
- dificuldade para localizar regras de negócio;
- forte acoplamento entre módulos;
- dificuldade para testar;
- dificuldade para evoluir Web e Mobile em paralelo.

Por outro lado, dividir imediatamente a plataforma em microsserviços aumentaria
a complexidade operacional sem uma necessidade comprovada.

---

## 2. Decisão

A SmartBiz será desenvolvida como um Monólito Modular.

A plataforma continuará sendo uma única aplicação Laravel implantável, porém
será organizada internamente em módulos com:

- responsabilidades claras;
- limites explícitos;
- contratos públicos;
- regras de dependência;
- testes próprios;
- documentação própria;
- eventos próprios.

Cada módulo representará uma capacidade da plataforma.

Exemplos:

```text
CRM
Marketing
Finance
Calendar
Companies
Users
Audit
Workflow
Notifications
Reports
AI