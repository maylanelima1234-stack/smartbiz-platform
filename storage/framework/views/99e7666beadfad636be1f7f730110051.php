<?php echo csrf_field(); ?>
<div class="grid gap-5 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="text-sm font-medium text-slate-700">Nome *</label>
        <input name="name" value="<?php echo e(old('name', $lead->name ?? '')); ?>" required class="mt-1 w-full rounded-xl border-slate-300">
        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-sm text-rose-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>
    <div><label class="text-sm font-medium text-slate-700">E-mail</label><input type="email" name="email" value="<?php echo e(old('email', $lead->email ?? '')); ?>" class="mt-1 w-full rounded-xl border-slate-300"></div>
    <div><label class="text-sm font-medium text-slate-700">Telefone</label><input name="phone" value="<?php echo e(old('phone', $lead->phone ?? '')); ?>" class="mt-1 w-full rounded-xl border-slate-300"></div>
    <div><label class="text-sm font-medium text-slate-700">Pipeline</label><select name="pipeline_id" class="mt-1 w-full rounded-xl border-slate-300"><option value="">Selecione</option><?php $__currentLoopData = $pipelines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pipeline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($pipeline->id); ?>" <?php if(old('pipeline_id', $lead->pipeline_id ?? '') == $pipeline->id): echo 'selected'; endif; ?>><?php echo e($pipeline->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
    <div><label class="text-sm font-medium text-slate-700">Etapa</label><select name="stage_id" class="mt-1 w-full rounded-xl border-slate-300"><option value="">Selecione</option><?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($stage->id); ?>" <?php if(old('stage_id', $lead->stage_id ?? '') == $stage->id): echo 'selected'; endif; ?>><?php echo e($stage->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
    <div><label class="text-sm font-medium text-slate-700">Responsável</label><select name="owner_id" class="mt-1 w-full rounded-xl border-slate-300"><option value="">Não atribuído</option><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($user->id); ?>" <?php if(old('owner_id', $lead->owner_id ?? '') == $user->id): echo 'selected'; endif; ?>><?php echo e($user->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
    <div><label class="text-sm font-medium text-slate-700">Origem</label><input name="source" value="<?php echo e(old('source', $lead->source ?? 'Manual')); ?>" class="mt-1 w-full rounded-xl border-slate-300"></div>
    <div><label class="text-sm font-medium text-slate-700">Campanha</label><input name="campaign" value="<?php echo e(old('campaign', $lead->campaign ?? '')); ?>" class="mt-1 w-full rounded-xl border-slate-300"></div>
    <div><label class="text-sm font-medium text-slate-700">Valor</label><input type="number" min="0" step="0.01" name="value" value="<?php echo e(old('value', $lead->value ?? 0)); ?>" class="mt-1 w-full rounded-xl border-slate-300"></div>
    <div><label class="text-sm font-medium text-slate-700">Status</label><select name="status" class="mt-1 w-full rounded-xl border-slate-300"><?php $__currentLoopData = ['open'=>'Aberto','won'=>'Ganho','lost'=>'Perdido']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if(old('status', $lead->status ?? 'open') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
    <div><label class="text-sm font-medium text-slate-700">Prioridade</label><select name="priority" class="mt-1 w-full rounded-xl border-slate-300"><?php $__currentLoopData = ['low'=>'Baixa','normal'=>'Normal','high'=>'Alta','urgent'=>'Urgente']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($value); ?>" <?php if(old('priority', $lead->priority ?? 'normal') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
    <div><label class="text-sm font-medium text-slate-700">Score</label><input type="number" min="0" max="100" name="score" value="<?php echo e(old('score', $lead->score ?? 0)); ?>" class="mt-1 w-full rounded-xl border-slate-300"></div>
    <div><label class="text-sm font-medium text-slate-700">Próximo follow-up</label><input type="datetime-local" name="next_follow_up_at" value="<?php echo e(old('next_follow_up_at', isset($lead) && $lead->next_follow_up_at ? $lead->next_follow_up_at->format('Y-m-d\\TH:i') : '')); ?>" class="mt-1 w-full rounded-xl border-slate-300"></div>
    <div class="sm:col-span-2"><label class="text-sm font-medium text-slate-700">Observações</label><textarea name="notes" rows="5" class="mt-1 w-full rounded-xl border-slate-300"><?php echo e(old('notes', $lead->notes ?? '')); ?></textarea></div>
</div>
<div class="mt-6 flex justify-end gap-3">
    <a href="<?php echo e(route('leads.index')); ?>" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700">Cancelar</a>
    <button class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white">Salvar lead</button>
</div>
<?php /**PATH C:\laragon\www\smartbiz-enterprise\resources\views/crm/leads/_form.blade.php ENDPATH**/ ?>