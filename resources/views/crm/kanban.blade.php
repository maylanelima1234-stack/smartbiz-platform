@extends('layouts.smartbiz')

@section('title', 'CRM — Pipeline')

@push('styles')
    @include('crm.partials.theme')
@endpush

@section('content')
<div class="crm-shell crm-page"><div class="crm-container space-y-4">
    <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
        <div><div class="crm-muted text-xs font-bold uppercase tracking-[.16em]">CRM</div><h1 class="crm-title mt-1">Pipeline operacional</h1><p class="crm-subtitle">Filtre, priorize e mova oportunidades sem sair do funil.</p></div>
        <a href="{{ route('crm.leads.create') }}" class="crm-btn crm-btn-primary">+ Novo lead</a>
    </div>
    @include('crm.partials.nav')

    <form method="GET" class="crm-card p-4">
        <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-6">
            <input name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Buscar lead, e-mail ou telefone" class="rounded-xl border border-[var(--crm-border)] bg-[var(--crm-card-2)] px-3 py-2 text-sm xl:col-span-2">
            <select name="priority" class="rounded-xl border border-[var(--crm-border)] bg-[var(--crm-card-2)] px-3 py-2 text-sm"><option value="">Todas as prioridades</option>@foreach(['low'=>'Baixa','normal'=>'Média','high'=>'Alta','urgent'=>'Urgente'] as $value=>$label)<option value="{{ $value }}" @selected(($filters['priority'] ?? '')===$value)>{{ $label }}</option>@endforeach</select>
            <select name="owner" class="rounded-xl border border-[var(--crm-border)] bg-[var(--crm-card-2)] px-3 py-2 text-sm"><option value="">Todos responsáveis</option>@foreach($owners as $owner)<option value="{{ $owner->id }}" @selected((string)($filters['owner'] ?? '')===(string)$owner->id)>{{ $owner->name }}</option>@endforeach</select>
            <label class="flex items-center gap-2 rounded-xl border border-[var(--crm-border)] bg-[var(--crm-card-2)] px-3 py-2 text-sm"><input type="checkbox" name="mine" value="1" @checked(($filters['mine'] ?? null)==='1')> Meus leads</label>
            <div class="flex gap-2"><button class="crm-btn crm-btn-primary flex-1">Aplicar</button><a href="{{ route('crm.kanban') }}" class="crm-btn">Limpar</a></div>
        </div>
    </form>

    <div id="kanban-feedback" class="hidden rounded-xl border px-4 py-3 text-sm font-bold"></div>

    @forelse($pipelines as $pipeline)
        <section class="space-y-3">
            <div class="flex items-center justify-between"><div><h2 class="text-sm font-black">{{ $pipeline->name }}</h2><p class="crm-muted text-xs"><span data-pipeline-count>{{ $pipeline->stages->sum(fn($s)=>$s->leads->count()) }}</span> oportunidades filtradas</p></div></div>
            <div class="crm-kanban" data-kanban>
                @foreach($pipeline->stages as $stage)
                    <div class="crm-column" data-stage-id="{{ $stage->id }}">
                        <div class="crm-column-head"><div><div class="text-xs font-black">{{ $stage->name }}</div><div class="crm-muted mt-1 text-[10px]" data-stage-value>R$ {{ number_format((float)$stage->leads->sum('value'),2,',','.') }}</div></div><span class="crm-badge" data-stage-count>{{ $stage->leads->count() }}</span></div>
                        <div class="min-h-[440px] py-1" data-dropzone>
                            @foreach($stage->leads as $lead)
                                <article class="crm-lead-card" draggable="true" data-lead-id="{{ $lead->id }}" data-value="{{ (float)$lead->value }}">
                                    <div class="flex items-start justify-between gap-3"><a href="{{ route('crm.leads.show',$lead) }}" class="text-sm font-extrabold hover:text-violet-400">{{ $lead->name }}</a>@if($lead->is_favorite)<span class="text-amber-400">★</span>@endif</div>
                                    <div class="crm-muted mt-1 text-[11px]">{{ $lead->owner?->name ?? 'Não atribuído' }}</div>
                                    <div class="mt-3 flex flex-wrap gap-1">@foreach($lead->tags->take(2) as $tag)<span class="crm-badge">{{ $tag->name }}</span>@endforeach</div>
                                    <div class="mt-4 flex items-center justify-between"><span class="crm-badge">{{ ucfirst($lead->priority ?? 'normal') }}</span><strong class="text-xs">R$ {{ number_format((float)$lead->value,2,',','.') }}</strong></div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @empty<div class="crm-card crm-empty">Nenhum pipeline ativo encontrado.</div>@endforelse
</div></div>
<script>
(()=>{
    let dragged=null, origin=null;
    const feedback=document.getElementById('kanban-feedback');
    const money=new Intl.NumberFormat('pt-BR',{style:'currency',currency:'BRL'});
    const refresh=()=>document.querySelectorAll('[data-stage-id]').forEach(col=>{
        const cards=[...col.querySelectorAll('[data-lead-id]')];
        col.querySelector('[data-stage-count]').textContent=cards.length;
        col.querySelector('[data-stage-value]').textContent=money.format(cards.reduce((sum,c)=>sum+Number(c.dataset.value||0),0));
    });
    const show=(message,ok=true)=>{feedback.textContent=message;feedback.className=`rounded-xl border px-4 py-3 text-sm font-bold ${ok?'border-emerald-500/40 bg-emerald-500/10 text-emerald-300':'border-red-500/40 bg-red-500/10 text-red-300'}`;setTimeout(()=>feedback.classList.add('hidden'),2500)};
    document.querySelectorAll('[data-lead-id]').forEach(card=>{
        card.addEventListener('dragstart',()=>{dragged=card;origin=card.parentElement;card.style.opacity='.45'});
        card.addEventListener('dragend',()=>{card.style.opacity='';dragged=null;origin=null});
    });
    document.querySelectorAll('[data-stage-id]').forEach(col=>{
        col.addEventListener('dragover',e=>{e.preventDefault();col.classList.add('ring-2','ring-violet-500/50')});
        col.addEventListener('dragleave',()=>col.classList.remove('ring-2','ring-violet-500/50'));
        col.addEventListener('drop',async e=>{
            e.preventDefault();col.classList.remove('ring-2','ring-violet-500/50');if(!dragged)return;
            const zone=col.querySelector('[data-dropzone]'),stageId=col.dataset.stageId,leadId=dragged.dataset.leadId;
            if(origin===zone)return;
            zone.prepend(dragged);refresh();
            try{
                const r=await fetch(`{{ url('/crm/kanban/leads') }}/${leadId}/move`,{method:'PATCH',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({stage_id:Number(stageId)})});
                if(!r.ok)throw new Error();show('Lead movido com sucesso.');
            }catch(error){origin.prepend(dragged);refresh();show('Não foi possível mover o lead. A alteração foi desfeita.',false)}
        });
    });
    refresh();
})();
</script>
@endsection
