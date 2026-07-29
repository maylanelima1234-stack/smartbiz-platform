<style>
    /* CRM herda o mesmo Design System, tema e fundo do painel principal. */
    .crm-shell{
        --crm-bg:var(--sb-bg,#0f0b17);
        --crm-card:var(--sb-surface,#171320);
        --crm-card-2:var(--sb-surface-2,#21192e);
        --crm-text:var(--sb-text,#fff);
        --crm-muted:var(--sb-text-muted,#b7b7c9);
        --crm-border:var(--sb-border,rgba(255,255,255,.07));
        --crm-primary:var(--sb-primary,#7c3aed);
        --crm-primary-hover:var(--sb-primary-hover,#8b5cf6);
        --crm-success:#22c55e;
        --crm-warning:#f59e0b;
        --crm-danger:#ef4444;
        color:var(--crm-text);
        background:transparent;
    }

    .crm-page{padding:0}
    .crm-container{width:100%;max-width:none;margin:0}
    .crm-card{
        background:var(--crm-card);
        border:1px solid var(--crm-border);
        border-radius:16px;
        box-shadow:var(--sb-shadow,0 12px 30px rgba(0,0,0,.10));
        color:var(--crm-text);
    }
    .crm-muted{color:var(--crm-muted)}
    .crm-title{font-size:clamp(1.42rem,1.8vw,1.9rem);line-height:1.12;font-weight:800;letter-spacing:-.035em;color:var(--crm-text)}
    .crm-subtitle{font-size:.9rem;color:var(--crm-muted);margin-top:5px}

    .crm-btn{
        display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:38px;
        border-radius:11px;padding:0 14px;font-size:12.5px;font-weight:750;
        border:1px solid var(--crm-border);transition:.18s ease;
        background:var(--crm-card);color:var(--crm-text);text-decoration:none;
    }
    .crm-btn:hover{transform:translateY(-1px);background:var(--crm-card-2);color:var(--crm-text)}
    .crm-btn-primary{background:var(--crm-primary);border-color:transparent;color:#fff}
    .crm-btn-primary:hover{background:var(--crm-primary-hover);color:#fff}

    .crm-nav{
        display:flex;gap:5px;overflow-x:auto;padding:5px;background:var(--crm-card);
        border:1px solid var(--crm-border);border-radius:14px;box-shadow:var(--sb-shadow,none);
    }
    .crm-nav a{white-space:nowrap;padding:8px 13px;border-radius:10px;font-size:12px;font-weight:700;color:var(--crm-muted);text-decoration:none}
    .crm-nav a:hover,.crm-nav a.active{background:var(--crm-card-2);color:var(--crm-text)}
    .crm-nav a.active{box-shadow:inset 0 0 0 1px color-mix(in srgb,var(--crm-primary) 36%,transparent)}

    .crm-metric{padding:16px}
    .crm-metric-label{font-size:11.5px;color:var(--crm-muted);font-weight:700}
    .crm-metric-value{font-size:26px;line-height:1;font-weight:850;margin-top:10px;letter-spacing:-.025em;color:var(--crm-text)}
    .crm-metric-note{font-size:11px;color:var(--crm-muted);margin-top:8px}
    .crm-icon{width:35px;height:35px;border-radius:11px;display:grid;place-items:center;background:var(--crm-card-2);color:#a78bfa}

    .crm-section-head{display:flex;align-items:center;justify-content:space-between;gap:15px;padding:16px 17px;border-bottom:1px solid var(--crm-border)}
    .crm-section-title{font-size:14.5px;font-weight:800;color:var(--crm-text)}
    .crm-section-subtitle{font-size:11.5px;color:var(--crm-muted);margin-top:3px}
    .crm-body{padding:17px}

    .crm-input,.crm-select,.crm-textarea{
        width:100%;border-radius:11px;border:1px solid var(--crm-border);
        background:var(--crm-card-2);color:var(--crm-text);font-size:13px;
        min-height:42px;padding:0 12px;outline:none;
    }
    .crm-textarea{padding:11px 12px;min-height:105px}
    .crm-input::placeholder,.crm-textarea::placeholder{color:var(--crm-muted)}
    .crm-input:focus,.crm-select:focus,.crm-textarea:focus{border-color:color-mix(in srgb,var(--crm-primary) 72%,transparent);box-shadow:0 0 0 3px color-mix(in srgb,var(--crm-primary) 15%,transparent)}
    .crm-label{display:block;font-size:12px;font-weight:750;margin-bottom:7px;color:var(--crm-muted)}

    .crm-table{width:100%;border-collapse:collapse;color:var(--crm-text)}
    .crm-table th{font-size:10.5px;text-transform:uppercase;letter-spacing:.08em;text-align:left;color:var(--crm-muted);padding:11px 14px;border-bottom:1px solid var(--crm-border)}
    .crm-table td{padding:12px 14px;border-bottom:1px solid var(--crm-border);font-size:12.5px;color:var(--crm-text)}
    .crm-table tr:last-child td{border-bottom:0}
    .crm-table tbody tr:hover{background:var(--crm-card-2)}

    .crm-badge{display:inline-flex;align-items:center;gap:6px;border-radius:999px;padding:5px 9px;font-size:10.5px;font-weight:800;background:var(--crm-card-2);border:1px solid var(--crm-border);color:var(--crm-text)}
    .crm-dot{width:6px;height:6px;border-radius:50%;background:currentColor}
    .crm-empty{text-align:center;padding:42px 18px;color:var(--crm-muted)}
    .crm-kpi-line{height:7px;background:var(--crm-card-2);border-radius:999px;overflow:hidden}
    .crm-kpi-line>span{display:block;height:100%;border-radius:inherit;background:linear-gradient(90deg,var(--crm-primary),#a78bfa)}

    .crm-kanban{display:grid;grid-auto-flow:column;grid-auto-columns:minmax(275px,1fr);gap:12px;overflow-x:auto;padding-bottom:8px}
    .crm-column{background:var(--crm-card-2);border:1px solid var(--crm-border);border-radius:15px;min-height:520px;color:var(--crm-text)}
    .crm-column-head{display:flex;align-items:center;justify-content:space-between;padding:13px 14px;border-bottom:1px solid var(--crm-border)}
    .crm-lead-card{margin:10px;padding:13px;border-radius:12px;background:var(--crm-card);border:1px solid var(--crm-border);cursor:grab;transition:.18s;color:var(--crm-text)}
    .crm-lead-card:hover{transform:translateY(-2px);border-color:color-mix(in srgb,var(--crm-primary) 48%,var(--crm-border))}

    /* Paginação Laravel/Tailwind dentro do tema SmartBiz. */
    .crm-shell nav[role="navigation"]{color:var(--crm-text)}
    .crm-shell nav[role="navigation"] span,.crm-shell nav[role="navigation"] a{border-color:var(--crm-border)!important;background:var(--crm-card)!important;color:var(--crm-text)!important}
    .crm-shell nav[role="navigation"] a:hover{background:var(--crm-card-2)!important}

    @media(max-width:767px){
        .crm-title{font-size:24px}.crm-grid-4{grid-template-columns:1fr 1fr!important}
        .crm-table-wrap{overflow-x:auto}.crm-hide-mobile{display:none}
    }
    @media(max-width:480px){.crm-grid-4{grid-template-columns:1fr!important}}
</style>
