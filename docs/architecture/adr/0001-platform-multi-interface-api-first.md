# ADR 0001 — Plataforma multi-interface e API-first

- Status: Proposto
- Data: 2026-07-29
- Responsáveis: SmartBiz Engineering

## Contexto

A SmartBiz atualmente possui uma interface web construída em Laravel e Blade.

O roadmap prevê o desenvolvimento de aplicativos para Android e iOS,
além de integrações com plataformas externas.

Criar regras de negócio separadas para cada interface aumentaria:

- duplicação;
- inconsistência;
- custo de manutenção;
- risco de segurança;
- divergência entre Web e Mobile.

Também seria inadequado permitir que os aplicativos acessassem diretamente
o banco de dados.

## Decisão

A SmartBiz será desenvolvida como uma plataforma multi-interface.

O backend Laravel será a fonte oficial das regras de negócio e dos dados.

As interfaces suportadas serão:

- painel web;
- API;
- aplicativo Android;
- aplicativo iOS;
- integrações externas.

A arquitetura seguirá uma abordagem API-first para os recursos que serão
compartilhados entre Web e Mobile.

API-first não significa que toda tela Blade deverá consumir a API imediatamente.

Significa que:

- casos de uso serão independentes da interface;
- contratos públicos serão definidos de forma explícita;
- regras de negócio não ficarão presas a Controllers Blade;
- funcionalidades móveis poderão reutilizar os mesmos casos de uso;
- novos recursos deverão avaliar sua exposição pela API.

## Fluxo esperado

```text
Web Controller ─┐
                ├─→ Application Action → Domain → Event
API Controller ─┤                           │
                │                           ├─→ Audit
Android ────────┤                           ├─→ Workflow
iOS ────────────┘                           └─→ Notification