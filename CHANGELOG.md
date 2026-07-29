# 0.8.0-alpha — SmartBiz Automation & Audit

- Workflow Engine multiempresa.
- Construtor de automações para leads.
- Gatilhos de criação, atualização e mudança de etapa.
- Condições configuráveis.
- Ações de notificação, prioridade e follow-up.
- Teste manual, ativação, pausa e histórico de execuções.
- Central de auditoria com filtros.

# Changelog

## 0.6.0-alpha — CRM Velocity

- Pipeline com filtros por busca, prioridade, responsável e “meus leads”.
- Contadores e valores das colunas atualizados após drag-and-drop.
- Feedback visual de sucesso/erro e rollback automático em falhas.
- Centro de Atenção no dashboard com atividades vencidas e leads parados.
- Indicadores de atividades do dia e leads sem atualização há mais de 3 dias.
- Isolamento multiempresa preservado.

# Changelog

## 0.2.1-alpha — Kernel UX

- Pesquisa global real em empresas, leads e usuários.
- Pesquisa com atraso controlado para evitar requisições excessivas.
- Navegação por teclado com setas e Enter.
- Ações rápidas para criar empresa, lead e usuário.
- Favoritos persistidos no navegador.
- Histórico de itens acessados recentemente.
- Centro de notificações conectado ao banco.
- Contador de notificações não lidas.
- Marcação individual e em massa como lida.
- Notificações automáticas ao cadastrar empresa, lead ou usuário.
- Nova migration `smart_notifications`.
- Assets front-end compilados.

## [0.4.1] - 2026-07-28

### Corrigido
- Adm. Tráfego deixou de depender de `company_id` para acessar a plataforma.
- Perfis internos SmartBiz agora carregam permissões diretamente do papel global.
- Alternância de empresa liberada para perfis internos multiempresa.
- Dashboard do Adm. Tráfego passou a exibir visão global e seletor de empresa ativa.
- Cadastro e edição de perfis internos limpam automaticamente o vínculo com empresa cliente.

## 0.4.2 - 2026-07-28

### Corrigido
- Removidas autorizações duplicadas nos controllers do CRM.
- Removidas verificações duplicadas de licença nas páginas do CRM.
- Preservado o isolamento multiempresa através do `PlatformContext` e dos services.
- Padronizados os controllers do CRM para preparar a evolução do Lead 360°.

## 0.5.0-alpha — Lead 360° Foundation
- Timeline automática dedicada por lead.
- Health Score dinâmico de 0 a 100.
- Upload, download e exclusão segura de arquivos por empresa.
- Lead 360° ampliado com comentários, timeline, arquivos e indicador de saúde.
- Registro automático de criação, atualização, mudança de etapa e arquivos.

## [0.7.0-alpha] - 2026-07-28

### Adicionado
- Enterprise Workspace e Centro de Operações orientado a ações.
- Feed unificado de operações com filtros e paginação.
- Painel de prioridades reais do CRM.
- Resumo de agenda e gráfico real de leads dos últimos sete dias.
- Entrada do Feed de Operações na navegação principal.

### Melhorado
- Dashboard principal agora usa dados reais em vez de barras demonstrativas.
- Métricas de conversão, vendas, pipeline e atividades atrasadas.
- Isolamento de consultas pela empresa ativa.

## 0.9.0-alpha — Core CRM & Executive

- Corrige o cadastro de leads bloqueado por divergência entre `Indicação`/`indicacao` e `medium`/`normal`.
- Adiciona Enums e normalização centralizada para origem, prioridade e status.
- Adiciona mensagens amigáveis de validação nos formulários do CRM.
- Adiciona Dashboard Executivo e histórico de execuções do Workflow Engine.
- Melhora a coerência entre interface, validação e banco de dados.
