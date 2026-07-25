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
     <?php $__env->slot('header', null, []); ?> <?php if (isset($component)) { $__componentOriginald5bf42194ba41446d40a4775b4411879 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald5bf42194ba41446d40a4775b4411879 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.smart.page-header','data' => ['eyebrow' => 'Smart CRM','title' => 'Novo lead','subtitle' => 'Cadastre uma nova oportunidade comercial.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('smart.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['eyebrow' => 'Smart CRM','title' => 'Novo lead','subtitle' => 'Cadastre uma nova oportunidade comercial.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald5bf42194ba41446d40a4775b4411879)): ?>
<?php $attributes = $__attributesOriginald5bf42194ba41446d40a4775b4411879; ?>
<?php unset($__attributesOriginald5bf42194ba41446d40a4775b4411879); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald5bf42194ba41446d40a4775b4411879)): ?>
<?php $component = $__componentOriginald5bf42194ba41446d40a4775b4411879; ?>
<?php unset($__componentOriginald5bf42194ba41446d40a4775b4411879); ?>
<?php endif; ?> <?php $__env->endSlot(); ?>
    <div class="py-8"><div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8"><div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200"><form method="POST" action="<?php echo e(route('leads.store')); ?>"><?php echo $__env->make('crm.leads._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></form></div></div></div>
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
<?php /**PATH C:\laragon\www\smartbiz-enterprise\resources\views/crm/leads/create.blade.php ENDPATH**/ ?>