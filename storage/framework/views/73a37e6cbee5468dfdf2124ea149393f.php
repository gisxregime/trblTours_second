<div class="bg-white pb-10">
    <section class="mx-auto w-full max-w-6xl px-4 pt-6 sm:px-6 lg:px-8">
        <div class="mb-4 rounded-2xl border border-[#d4a563]/35 bg-white px-6 py-4 shadow-[0_10px_24px_-16px_rgba(122,85,50,0.55)]">
            <h2 class="text-xl font-semibold leading-tight text-slate-900"><?php echo e(__('Guide Profile')); ?></h2>
        </div>

        <article class="overflow-hidden rounded-2xl border border-[#d4a563]/35 bg-white shadow-[0_16px_34px_-20px_rgba(122,85,50,0.6)]">
            <div class="relative h-56 w-full bg-gradient-to-r from-[#d4a563] via-[#c69958] to-[#b8894b]">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($guide['cover_photo_path'] !== ''): ?>
                    <img src="<?php echo e(asset('storage/'.$guide['cover_photo_path'])); ?>" alt="Guide cover photo" class="h-full w-full object-cover">
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="relative px-6 pb-6 pt-16 sm:px-8">
                <div class="absolute -top-14 left-6 sm:left-8">
                    <div class="h-28 w-28 overflow-hidden rounded-full border-4 border-white bg-[#f7ead7] shadow-[0_10px_20px_-8px_rgba(122,85,50,0.65)] ring-2 ring-[#d4a563]/40">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($guide['profile_photo_path'] !== ''): ?>
                            <img src="<?php echo e(asset('storage/'.$guide['profile_photo_path'])); ?>" alt="Guide profile photo" class="h-full w-full object-cover">
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900"><?php echo e($guide['display_name']); ?></h3>
                        <p class="mt-1 text-sm text-slate-500">
                            <?php echo e($guide['region'] ?: 'Region not set'); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($guide['city_municipality'] !== ''): ?>
                                · <?php echo e($guide['city_municipality']); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($guide['barangay'] !== ''): ?>
                                · <?php echo e($guide['barangay']); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                    </div>

                    <div class="flex gap-2">
                        <a href="<?php echo e(route('dashboard.guide.profile.edit')); ?>" class="inline-flex items-center rounded-lg bg-[#d4a563] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#c69958] shadow-[0_8px_18px_-10px_rgba(122,85,50,0.65)]">
                            Edit Profile
                        </a>
                        <a href="<?php echo e(route('dashboard.guide')); ?>" class="inline-flex items-center rounded-lg border border-[#d4a563]/45 bg-[#fff7ec] px-4 py-2 text-sm font-semibold text-[#7a5532] transition hover:bg-[#f7ead7]">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </article>

        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <article class="rounded-2xl border border-[#d4a563]/30 bg-white p-5 shadow-[0_10px_24px_-16px_rgba(122,85,50,0.5)]">
                <p class="text-sm text-slate-500">Completed Tours</p>
                <p class="mt-2 text-3xl font-bold text-slate-900"><?php echo e($completedBookings); ?></p>
            </article>

            <article class="rounded-2xl border border-[#d4a563]/30 bg-white p-5 shadow-[0_10px_24px_-16px_rgba(122,85,50,0.5)]">
                <p class="text-sm text-slate-500">Average Rating</p>
                <p class="mt-2 text-3xl font-bold text-slate-900"><?php echo e(number_format($averageRating, 1)); ?>/5</p>
            </article>

            <article class="rounded-2xl border border-[#d4a563]/30 bg-white p-5 shadow-[0_10px_24px_-16px_rgba(122,85,50,0.5)]">
                <p class="text-sm text-slate-500">Verification</p>
                <p class="mt-2 text-2xl font-bold text-slate-900 capitalize"><?php echo e($verificationStatus); ?></p>
                <p class="mt-1 text-xs uppercase tracking-wide text-slate-500"><?php echo e($documentStatus); ?></p>
            </article>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-[2fr_1fr]">
            <article class="rounded-2xl border border-[#d4a563]/30 bg-white p-6 shadow-[0_10px_24px_-16px_rgba(122,85,50,0.5)]">
                <h4 class="text-lg font-semibold text-slate-900">About</h4>
                <p class="mt-3 whitespace-pre-line text-sm leading-7 text-slate-700"><?php echo e($guide['bio']); ?></p>
            </article>

            <aside class="space-y-4">
                <article class="rounded-2xl border border-[#d4a563]/30 bg-white p-5 shadow-[0_10px_24px_-16px_rgba(122,85,50,0.5)]">
                    <h5 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Contact</h5>
                    <p class="mt-3 text-sm text-slate-700">Phone: <?php echo e($guide['phone_number'] !== '' ? $guide['phone_number'] : 'Not set'); ?></p>
                    <p class="mt-1 text-sm text-slate-700">Date of Birth: <?php echo e($guide['date_of_birth'] !== '' ? $guide['date_of_birth'] : 'Not set'); ?></p>
                </article>

                <article class="rounded-2xl border border-[#d4a563]/30 bg-white p-5 shadow-[0_10px_24px_-16px_rgba(122,85,50,0.5)]">
                    <h5 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Guide Details</h5>
                    <p class="mt-3 text-sm text-slate-700">Specialty: <?php echo e($guide['specialty'] ?: 'Not set'); ?></p>
                    <p class="mt-1 text-sm text-slate-700">Experience: <?php echo e($guide['years_of_experience'] !== '' ? $guide['years_of_experience'].' years' : 'Not set'); ?></p>
                </article>
            </aside>
        </div>
    </section>
</div>
<?php /**PATH /home/mistah-regime/tribaltours/resources/views/livewire/guide/guide-profile.blade.php ENDPATH**/ ?>