<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title',
    'subtitle' => null,
    'eyebrow' => null,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'title',
    'subtitle' => null,
    'eyebrow' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->merge(['class' => 'flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between'])); ?>>
    <div class="min-w-0">
        <?php if($eyebrow): ?>
            <p class="mb-1 text-xs font-semibold uppercase tracking-[0.18em] text-indigo-600"><?php echo e($eyebrow); ?></p>
        <?php endif; ?>

        <h1 class="truncate text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl"><?php echo e($title); ?></h1>

        <?php if($subtitle): ?>
            <p class="mt-1 max-w-3xl text-sm text-slate-500 sm:text-base"><?php echo e($subtitle); ?></p>
        <?php endif; ?>
    </div>

    <?php if(isset($actions)): ?>
        <div class="flex shrink-0 flex-wrap items-center gap-2"><?php echo e($actions); ?></div>
    <?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\smartbiz-enterprise\resources\views/components/smart/page-header.blade.php ENDPATH**/ ?>