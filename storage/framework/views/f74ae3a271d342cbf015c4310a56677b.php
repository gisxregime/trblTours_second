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
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="text-xl font-semibold leading-tight text-slate-900">
            <?php echo e(__('Admin Dashboard')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="bg-slate-100 py-8">
        <div class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 sm:px-6 lg:px-8">
            <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Total Users</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900"><?php echo e(number_format($stats['total_users'])); ?></p>
                </article>
                <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Active Guides</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900"><?php echo e(number_format($stats['active_guides'])); ?></p>
                </article>
                <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Pending Guide Approvals</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900"><?php echo e(number_format($stats['pending_guide_approvals'])); ?></p>
                </article>
                <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Featured Tours</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900"><?php echo e(number_format($stats['featured_tours'])); ?></p>
                </article>
            </section>

            <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h3 class="text-lg font-semibold text-slate-900">Admin Feature Center</h3>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Ready</span>
                </div>
                <div class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <article class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <h4 class="text-sm font-semibold text-slate-900">User Monitoring</h4>
                        <p class="mt-1 text-sm text-slate-600">Track account growth and role distribution from live records.</p>
                    </article>
                    <article class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <h4 class="text-sm font-semibold text-slate-900">Guide Approval Queue</h4>
                        <p class="mt-1 text-sm text-slate-600">See pending guide approvals and prioritize compliance checks.</p>
                    </article>
                    <article class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <h4 class="text-sm font-semibold text-slate-900">Tour Oversight</h4>
                        <p class="mt-1 text-sm text-slate-600">Monitor featured tours count to keep homepage highlights fresh.</p>
                    </article>
                    <article class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <h4 class="text-sm font-semibold text-slate-900">Recent Accounts</h4>
                        <p class="mt-1 text-sm text-slate-600">Review new signups and statuses in one operational view.</p>
                    </article>
                </div>
            </section>

            <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Recent User Accounts</h3>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="text-slate-500">
                            <tr>
                                <th class="py-2 pe-4">Name</th>
                                <th class="py-2 pe-4">Email</th>
                                <th class="py-2 pe-4">Role</th>
                                <th class="py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="border-t border-slate-100">
                                    <td class="py-3 pe-4"><?php echo e($user->name); ?></td>
                                    <td class="py-3 pe-4"><?php echo e($user->email); ?></td>
                                    <td class="py-3 pe-4"><?php echo e(str($user->role)->replace('_', ' ')->title()); ?></td>
                                    <td class="py-3">
                                        <span class="rounded-full px-2 py-1 text-xs font-medium <?php echo e($user->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'); ?>">
                                            <?php echo e(str($user->status)->title()); ?>

                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr class="border-t border-slate-100">
                                    <td colspan="4" class="py-4 text-slate-500">No users found.</td>
                                </tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
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
<?php /**PATH /home/mistah-regime/tribaltours/resources/views/dashboards/admin.blade.php ENDPATH**/ ?>