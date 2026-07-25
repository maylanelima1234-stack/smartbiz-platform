<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
 <?php $__env->slot('header', null, []); ?> <div class="flex items-center justify-between"><div><h2 class="text-xl font-semibold text-gray-800">Leads</h2><p class="text-sm text-gray-500">Pesquisa e filtros comerciais</p></div><div class="flex gap-2"><a href="<?php echo e(route('leads.create')); ?>" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm text-white">Novo lead</a><a href="<?php echo e(route('crm.kanban')); ?>" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm text-slate-700">Kanban</a></div></div> <?php $__env->endSlot(); ?>
<div class="py-8"><div class="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
<form method="GET" class="grid gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-200 md:grid-cols-6">
<input name="search" value="<?php echo e($filters['search'] ?? ''); ?>" placeholder="Nome, e-mail ou telefone" class="rounded-lg border-gray-300 text-sm md:col-span-2">
<select name="status" class="rounded-lg border-gray-300 text-sm"><option value="">Todos os status</option><?php $__currentLoopData = ['open'=>'Aberto','won'=>'Ganho','lost'=>'Perdido']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($v); ?>" <?php if(($filters['status']??'')===$v): echo 'selected'; endif; ?>><?php echo e($l); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
<select name="priority" class="rounded-lg border-gray-300 text-sm"><option value="">Prioridade</option><?php $__currentLoopData = ['low'=>'Baixa','normal'=>'Normal','high'=>'Alta','urgent'=>'Urgente']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($v); ?>" <?php if(($filters['priority']??'')===$v): echo 'selected'; endif; ?>><?php echo e($l); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
<select name="source" class="rounded-lg border-gray-300 text-sm"><option value="">Origem</option><?php $__currentLoopData = $sources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($source); ?>" <?php if(($filters['source']??'')===$source): echo 'selected'; endif; ?>><?php echo e($source); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
<div class="flex gap-2"><button class="flex-1 rounded-lg bg-indigo-600 px-3 py-2 text-sm text-white">Filtrar</button><a href="<?php echo e(route('leads.index')); ?>" class="rounded-lg border px-3 py-2 text-sm">Limpar</a></div>
<label class="flex items-center gap-2 text-sm md:col-span-6"><input type="checkbox" name="favorite" value="1" <?php if(($filters['favorite']??'')==='1'): echo 'checked'; endif; ?>> Somente favoritos</label>
</form>
<div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-200"><div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200 text-sm"><thead class="bg-gray-50"><tr><th class="px-5 py-3 text-left">Lead</th><th class="px-5 py-3 text-left">Etapa</th><th class="px-5 py-3 text-left">Origem</th><th class="px-5 py-3 text-left">Responsável</th><th class="px-5 py-3 text-right">Valor</th></tr></thead><tbody class="divide-y"><?php $__empty_1 = true; $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr class="hover:bg-gray-50"><td class="px-5 py-4"><a href="<?php echo e(route('leads.show',$lead)); ?>" class="font-medium text-indigo-700"><?php echo e($lead->is_favorite ? '★ ' : ''); ?><?php echo e($lead->name); ?></a><p class="text-xs text-gray-500"><?php echo e($lead->email ?: $lead->phone); ?></p></td><td class="px-5 py-4"><?php echo e($lead->stage?->name ?? 'Sem etapa'); ?></td><td class="px-5 py-4"><?php echo e($lead->source ?: '—'); ?></td><td class="px-5 py-4"><?php echo e($lead->owner?->name ?? 'Não atribuído'); ?></td><td class="px-5 py-4 text-right font-medium">R$ <?php echo e(number_format((float)$lead->value,2,',','.')); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="5" class="p-8 text-center text-gray-500">Nenhum lead encontrado.</td></tr><?php endif; ?></tbody></table></div></div>
<?php echo e($leads->links()); ?>

</div></div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\smartbiz-enterprise\resources\views/crm/leads/index.blade.php ENDPATH**/ ?>