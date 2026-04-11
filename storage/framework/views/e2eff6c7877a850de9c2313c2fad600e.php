<div class="min-h-screen bg-white py-8">
    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Profile Header Section -->
        <div class="mb-8 overflow-hidden rounded-2xl border border-[#d4a563]/35 bg-white shadow-[0_18px_36px_-22px_rgba(122,85,50,0.62)]">
            <!-- Background Image -->
            <div class="relative h-48 bg-gradient-to-r from-[#d4a563] via-[#c69958] to-[#b8894b] sm:h-56 md:h-64">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($profile && isset($profile['cover_photo_path'])): ?>
                    <img
                        src="<?php echo e(asset('storage/'.$profile['cover_photo_path'])); ?>"
                        alt="Cover"
                        class="h-full w-full object-cover"
                    />
                <?php else: ?>
                    <div class="h-full w-full bg-gradient-to-r from-[#e7c796] via-[#d4a563] to-[#c69958]"></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Profile Content -->
            <div class="relative px-6 pb-6 sm:px-8 md:px-10">
                <div class="flex flex-col gap-6 md:flex-row md:items-start">
                    <!-- Profile Picture and Info -->
                    <div class="flex flex-col md:flex-row md:items-end md:gap-6">
                        <!-- Profile Picture -->
                        <div class="relative -mt-16 mb-4 md:mb-0">
                            <div class="relative h-32 w-32 rounded-full border-4 border-white shadow-[0_10px_22px_-10px_rgba(122,85,50,0.72)] ring-2 ring-[#d4a563]/40">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($profile && isset($profile['profile_photo_path'])): ?>
                                    <img
                                        src="<?php echo e(asset('storage/'.$profile['profile_photo_path'])); ?>"
                                        alt="<?php echo e($user->full_name ?? $user->name); ?>"
                                        class="h-full w-full rounded-full object-cover"
                                    />
                                <?php elseif($user->profile_photo_path ?? null): ?>
                                    <img
                                        src="<?php echo e(asset('storage/'.$user->profile_photo_path)); ?>"
                                        alt="<?php echo e($user->full_name ?? $user->name); ?>"
                                        class="h-full w-full rounded-full object-cover"
                                    />
                                <?php else: ?>
                                    <div class="flex h-full w-full items-center justify-center rounded-full bg-gradient-to-br from-[#d4a563] to-[#b8894b] text-3xl font-bold text-white">
                                        <?php echo e(substr($user->full_name ?? $user->name, 0, 1)); ?>

                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <!-- Name and Location -->
                        <div class="flex-grow">
                            <h1 class="text-2xl font-bold text-[#7a5532] md:text-3xl">
                                <?php echo e($user->full_name ?? $user->name); ?>

                            </h1>
                            <p class="mt-1 flex items-center gap-2 text-[#7a5532]">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                <span><?php echo e($user->region ?? 'Location not specified'); ?></span>
                            </p>

                            <!-- Completion Bar -->
                            <div class="mt-3">
                                <div class="mb-1 flex items-center justify-between">
                                    <span class="text-xs font-semibold text-[#7a5532]">Profile Completion</span>
                                    <span class="text-xs font-semibold text-[#8a6746]"><?php echo e($completionPercentage); ?>%</span>
                                </div>
                                <div class="h-2.5 w-full overflow-hidden rounded-full bg-[#f3e3c9] ring-1 ring-[#d4a563]/55 shadow-[0_6px_14px_-10px_rgba(122,85,50,0.8)]">
                                    <div
                                        class="h-full bg-[#d4a563] transition-all duration-300"
                                        style="width: <?php echo e($completionPercentage); ?>%"
                                    ></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid gap-8 lg:grid-cols-3">
            <!-- Left Column - Stats and Tours -->
            <div class="space-y-8 lg:col-span-2">
                <!-- Stats Cards -->
                <div class="grid gap-4 grid-cols-2 md:grid-cols-4">
                    <!-- Total Earnings -->
                    <div class="rounded-xl border border-[#d4a563]/30 bg-white p-4 shadow-[0_10px_24px_-16px_rgba(122,85,50,0.5)]">
                        <p class="text-sm font-medium text-[#8a6746]">Total Earnings</p>
                        <p class="mt-2 text-2xl font-bold text-[#7a5532]">₱<?php echo e(number_format($totalEarnings, 0)); ?></p>
                        <p class="mt-1 text-xs text-[#9a7a58]">All time</p>
                    </div>

                    <!-- Active Tours -->
                    <div class="rounded-xl border border-[#d4a563]/30 bg-white p-4 shadow-[0_10px_24px_-16px_rgba(122,85,50,0.5)]">
                        <p class="text-sm font-medium text-[#8a6746]">Active Tours</p>
                        <p class="mt-2 text-2xl font-bold text-[#7a5532]"><?php echo e($activeTours); ?></p>
                        <p class="mt-1 text-xs text-[#9a7a58]">Available</p>
                    </div>

                    <!-- Featured Tours -->
                    <div class="rounded-xl border border-[#d4a563]/30 bg-white p-4 shadow-[0_10px_24px_-16px_rgba(122,85,50,0.5)]">
                        <p class="text-sm font-medium text-[#8a6746]">Featured Tours</p>
                        <p class="mt-2 text-2xl font-bold text-[#7a5532]"><?php echo e($featuredTours); ?></p>
                        <p class="mt-1 text-xs text-[#9a7a58]">Based on bookings</p>
                    </div>

                    <!-- Rating -->
                    <div class="rounded-xl border border-[#d4a563]/30 bg-white p-4 shadow-[0_10px_24px_-16px_rgba(122,85,50,0.5)]">
                        <p class="text-sm font-medium text-[#8a6746]">Rating</p>
                        <div class="mt-2 flex items-baseline gap-1">
                            <p class="text-2xl font-bold text-[#7a5532]"><?php echo e(number_format($averageRating, 1)); ?></p>
                            <p class="text-xs text-[#9a7a58]">/5.0</p>
                        </div>
                        <div class="mt-1 flex gap-0.5">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?>
                                <svg class="h-3 w-3 <?php echo e($i <= floor($averageRating) ? 'text-[#c69958]' : 'text-[#ecd3ad]'); ?>" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <!-- Profile Views -->
                    <div class="rounded-xl border border-[#d4a563]/30 bg-white p-4 shadow-[0_10px_24px_-16px_rgba(122,85,50,0.5)]">
                        <p class="text-sm font-medium text-[#8a6746]">Profile Views</p>
                        <p class="mt-2 text-2xl font-bold text-[#7a5532]"><?php echo e($profileViews); ?></p>
                        <p class="mt-1 text-xs text-[#9a7a58]">This month</p>
                    </div>
                </div>

                <!-- Create New Tour Button -->
                <div class="flex justify-end gap-3">
                    <button
                        wire:click="openSettings"
                        type="button"
                        class="inline-flex items-center gap-2 rounded-lg border border-[#d4a563]/50 bg-white px-6 py-3 font-semibold text-[#7a5532] transition hover:bg-[#fff7ec] shadow-[0_8px_18px_-10px_rgba(122,85,50,0.45)]"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                        </svg>
                        Settings
                    </button>
                    <a href="<?php echo e(route('dashboard.guide.tours')); ?>" class="inline-flex items-center gap-2 rounded-lg bg-[#d4a563] px-6 py-3 font-semibold text-white transition hover:bg-[#c69958] shadow-[0_8px_18px_-10px_rgba(122,85,50,0.65)]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create New Tour
                    </a>
                </div>

                <!-- Tour Listings -->
                <div class="rounded-xl border border-[#d4a563]/30 bg-white p-6 shadow-[0_10px_24px_-16px_rgba(122,85,50,0.5)]">
                    <h2 class="mb-4 text-xl font-bold text-[#7a5532]">Your Tour Listings</h2>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($guideTours->isEmpty()): ?>
                        <div class="rounded-lg border-2 border-dashed border-[#d4a563]/45 bg-[#fff7ec] py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-[#c69958]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="mt-2 text-sm text-[#8a6746]">No tour listings yet. Create your first tour!</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $guideTours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div wire:key="tour-<?php echo e($tour->id); ?>" class="flex items-center justify-between rounded-lg border border-[#d4a563]/30 bg-white p-4 transition hover:bg-[#fff7ec]">
                                    <div class="flex-grow">
                                        <h3 class="font-semibold text-[#7a5532]"><?php echo e($tour->title ?? $tour->name); ?></h3>
                                        <p class="mt-1 text-sm text-[#8a6746]">
                                            <span class="inline-block rounded bg-[#f7ead7] px-2 py-1 text-xs font-medium text-[#7a5532]">
                                                <?php echo e($tour->region ?? 'Region'); ?>

                                            </span>
                                        </p>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="<?php echo e(route('dashboard.guide.tours', ['edit' => $tour->id])); ?>" class="inline-flex items-center gap-1 rounded-md border border-[#d4a563]/45 bg-[#fff7ec] px-3 py-2 text-sm font-medium text-[#7a5532] transition hover:bg-[#f7ead7]">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                        <button
                                            wire:click="deleteTour(<?php echo e($tour->id); ?>)"
                                            wire:confirm="Are you sure you want to delete this tour?"
                                            type="button"
                                            class="inline-flex items-center gap-1 rounded-md border border-[#d4a563]/45 bg-white px-3 py-2 text-sm font-medium text-[#7a5532] transition hover:bg-[#f7ead7]"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <!-- Right Column - Reviews -->
            <div class="h-fit rounded-xl border border-[#d4a563]/30 bg-white p-6 shadow-[0_10px_24px_-16px_rgba(122,85,50,0.5)]">
                <h2 class="mb-4 text-xl font-bold text-[#7a5532]">Recent Reviews</h2>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recentReviews->isEmpty()): ?>
                    <div class="rounded-lg border-2 border-dashed border-[#d4a563]/45 bg-[#fff7ec] py-8 text-center">
                        <svg class="mx-auto h-10 w-10 text-[#c69958]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                        <p class="mt-2 text-sm text-[#8a6746]">No reviews yet</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $recentReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div wire:key="review-<?php echo e($review->id); ?>" class="border-b border-[#ecd3ad] pb-4 last:border-b-0 last:pb-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-grow">
                                        <p class="font-semibold text-[#7a5532]"><?php echo e($review->tourist->full_name ?? $review->tourist->name); ?></p>
                                        <div class="mt-1 flex gap-0.5">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?>
                                                <svg class="h-3.5 w-3.5 <?php echo e($i <= $review->rating ? 'text-[#c69958]' : 'text-[#ecd3ad]'); ?>" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                    <span class="text-xs text-[#9a7a58]"><?php echo e($review->created_at?->diffForHumans() ?? 'Recently'); ?></span>
                                </div>
                                <p class="mt-2 text-sm text-[#8a6746]"><?php echo e($review->review ?? $review->comment ?? 'No comment'); ?></p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <!-- Optional Alerts Section -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showCompletionReminder || $showVerificationNotice || $showRejectedNotice): ?>
            <div class="mt-8 space-y-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showCompletionReminder): ?>
                    <div class="rounded-lg border-l-4 border-[#7a5532] bg-[#ead9c6] p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-[#7a5532]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-[#5b3a26]">Complete your profile to start accepting bookings!</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showVerificationNotice): ?>
                    <div class="rounded-lg border-l-4 border-[#7a5532] bg-[#ead9c6] p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-[#7a5532]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-[#5b3a26]">Your documents are under review. We'll notify you within 24-48 hours.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showRejectedNotice): ?>
                    <div class="rounded-lg border-l-4 border-red-500 bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">
                                    <span class="font-semibold">Verification update: action needed.</span><br>
                                    <?php echo e($rejectionReason !== '' ? $rejectionReason : 'Please re-upload the required documents and update your profile.'); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH /home/mistah-regime/tribaltours/resources/views/livewire/guide/guide-dashboard.blade.php ENDPATH**/ ?>