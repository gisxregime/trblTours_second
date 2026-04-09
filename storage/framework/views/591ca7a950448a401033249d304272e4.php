<div class="bg-gradient-to-b from-slate-50 via-white to-emerald-50 py-10">
    <div class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 sm:px-6 lg:px-8">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold leading-tight text-slate-900"><?php echo e($title); ?></h2>
                    <p class="mt-1 text-sm text-slate-600">Create, update, and manage the tours tied to your guide account.</p>
                </div>

                <a href="<?php echo e(route('dashboard.guide')); ?>" class="inline-flex items-center rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-300">
                    Back to Dashboard
                </a>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="mb-6 flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900"><?php echo e($editingTourId ? 'Edit Tour' : 'Create Tour'); ?></h3>
                    <p class="text-sm text-slate-600">Keep the essential booking details current for travelers.</p>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editingTourId): ?>
                    <button type="button" wire:click="cancelEdit" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Cancel Edit
                    </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <form wire:submit.prevent="save" class="space-y-6">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700" for="tourTitle">Tour Title</label>
                        <input id="tourTitle" wire:model.live="form.title" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700" for="region">Region</label>
                        <input id="region" wire:model.live="form.region" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.region'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700" for="city">City</label>
                        <input id="city" wire:model.live="form.city" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700" for="category">Category</label>
                        <input id="category" wire:model.live="form.category" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700" for="duration_label">Duration Label</label>
                        <input id="duration_label" wire:model.live="form.duration_label" type="text" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.duration_label'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700" for="duration_hours">Duration Hours</label>
                        <input id="duration_hours" wire:model.live="form.duration_hours" type="number" min="1" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.duration_hours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700" for="price_per_person">Price Per Person</label>
                        <input id="price_per_person" wire:model.live="form.price_per_person" type="number" step="0.01" min="0" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.price_per_person'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700" for="available_on">Available On</label>
                        <input id="available_on" wire:model.live="form.available_on" type="date" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700" for="status">Status</label>
                        <select id="status" wire:model.live="form.status" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0">
                            <option value="draft">Draft</option>
                            <option value="pending_review">Pending Review</option>
                            <option value="active">Active</option>
                            <option value="paused">Paused</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700" for="summary">Summary</label>
                    <textarea id="summary" wire:model.live="form.summary" rows="5" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0"></textarea>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['form.summary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700">
                    <input wire:model.live="form.is_featured" type="checkbox" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    Featured tour
                </label>

                <div class="flex justify-end">
                    <button type="submit" class="rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-500">
                        <?php echo e($editingTourId ? 'Update Tour' : 'Create Tour'); ?>

                    </button>
                </div>
            </form>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between gap-3">
                <h3 class="text-lg font-semibold text-slate-900">Your Tours</h3>
                <span class="text-sm text-slate-500"><?php echo e($tours->count()); ?> total</span>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tours->isEmpty()): ?>
                <p class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4 text-sm text-slate-500">No tours yet. Create your first one above.</p>
            <?php else: ?>
                <div class="grid gap-4 lg:grid-cols-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <article class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-lg font-semibold text-slate-900"><?php echo e($tour->title ?? $tour->name); ?></p>
                                    <p class="mt-1 text-sm text-slate-600"><?php echo e($tour->region); ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tour->city): ?> · <?php echo e($tour->city); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></p>
                                    <p class="mt-1 text-sm text-slate-600"><?php echo e($tour->duration_label ?? 'Duration not set'); ?> · PHP <?php echo e(number_format((float) ($tour->price_per_person ?? $tour->price ?? 0), 2)); ?></p>
                                    <p class="mt-1 text-xs uppercase tracking-wide text-slate-500">Status: <?php echo e(str_replace('_', ' ', $tour->status ?? 'draft')); ?></p>
                                </div>

                                <div class="flex flex-col gap-2">
                                    <button type="button" wire:click="edit(<?php echo e($tour->id); ?>)" class="rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                                        Edit
                                    </button>
                                    <button type="button" wire:click="delete(<?php echo e($tour->id); ?>)" class="rounded-lg bg-rose-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-rose-500">
                                        Delete
                                    </button>
                                </div>
                            </div>

                            <p class="mt-4 text-sm leading-6 text-slate-700"><?php echo e($tour->summary ?? $tour->description ?? 'No summary provided.'); ?></p>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </section>
    </div>
</div>
<?php /**PATH /home/mistah-regime/tribaltours/resources/views/livewire/guide/guide-tours.blade.php ENDPATH**/ ?>