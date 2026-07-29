# ADR 0004 — Bounded Contexts e Linguagem Ubíqua

- Status: Proposto
- Data: 2026-07-29
- Responsáveis: SmartBiz Engineering
- Relacionados:
  - ADR 0001 — Plataforma multi-interface e API-first
  - ADR 0002 — Monólito modular
  - ADR 0003 — Estrutura orientada ao domínio

---

## 1. Contexto

A SmartBiz Enterprise será composta por diferentes áreas de negócio, como:

- CRM;
- Marketing;
- Financeiro;
- Agenda;
- Atendimento;
- Empresas;
- Usuários;
- Documentos;
- Relatórios;
- Inteligência Artificial;
- Assinaturas e cobrança da plataforma.

À medida que a plataforma crescer, uma mesma expressão poderá possuir
significados diferentes em áreas distintas.

Exemplos:

- uma conversão no Marketing não possui necessariamente o mesmo significado
  de uma conversão no CRM;
- um cliente comercial não é obrigatoriamente a empresa assinante da SmartBiz;
- uma atividade do CRM não é o mesmo que um evento da Agenda;
- uma cobrança da empresa cliente não é o mesmo que a cobrança da assinatura
  da própria SmartBiz.

Sem limites explícitos, existe risco de:

- entidades gigantes;
- dependências circulares;
- duplicidade de conceitos;
- regras espalhadas;
- nomes inconsistentes;
- módulos alterando dados que não lhes pertencem;
- dificuldade para desenvolver Web, Android e iOS com a mesma linguagem.

---

## 2. Decisão

A SmartBiz será dividida em Bounded Contexts.

Cada contexto possuirá:

- responsabilidade própria;
- linguagem própria;
- entidades próprias;
- regras próprias;
- contratos públicos;
- eventos públicos;
- implementação interna privada;
- documentação própria.

Um contexto será considerado dono dos dados e regras pertencentes à sua área.

Outros contextos não poderão alterar diretamente suas entidades internas.

---

## 3. Contextos oficiais

Os contextos iniciais da SmartBiz serão:

```text
Platform
Identity
Companies
CRM
Marketing
Finance
Calendar
Support
Documents
Knowledge
Reports
Notifications
Automation
AI
Billing