<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrblTours - Guide Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600&family=Asimovian:wght@400;700&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/css/feed.css', 'resources/js/app.js']); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Asimovian:wght@400;700&display=swap');
        .navbar-scroll { 
            background: rgba(92, 64, 51, 0.95);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        .navbar-scroll-gold {
            background: rgba(255, 215, 0, 0.95);
        }
    </style>
</head>
<body class="bg-offwhite font-inter">
    <!-- Fixed Top Navigation - Scroll Effect -->
    <header x-data="{ scrolled: false }" x-init="$watch('$window.scrollY', value => scrolled = value > 50)" class="fixed top-0 left-0 right-0 z-50 navbar-scroll shadow-lg">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 lg:px-8">
            <!-- Logo -->
            <a href="<?php echo e(route('dashboard.guide')); ?>" class="flex items-center gap-2">
                <div class="h-12 w-12 rounded-full bg-gold p-2 shadow-lg">
                    <span class="block h-full w-full bg-cream rounded-full flex items-center justify-center font-asimovian text-xl font-bold text-dark-brown">TT</span>
                </div>
                <span class="font-asimovian text-2xl font-bold text-gold md:block">TrblTours</span>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden items-center gap-1 lg:flex">
                <a href="<?php echo e(route('dashboard.guide')); ?>" class="rounded-lg bg-cream px-5 py-3 text-sm font-semibold text-dark-brown hover:bg-gold hover:text-offwhite transition">Dashboard</a>
                <a href="/dashboard/guide/tours" class="rounded-lg px-5 py-3 text-sm font-semibold text-gold hover:bg-cream transition">Tours</a>
                <a href="/dashboard/guide/requests" class="rounded-lg px-5 py-3 text-sm font-semibold text-gold hover:bg-cream transition">Bookings</a>
                <a href="/dashboard/guide/messages" class="rounded-lg px-5 py-3 text-sm font-semibold text-gold hover:bg-cream transition">Messages</a>
            </nav>

            <!-- Profile Dropdown -->
            <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                <button @click="open = !open" class="flex items-center gap-2 rounded-full bg-cream px-4 py-2 text-sm font-semibold text-dark-brown hover:bg-gold hover:text-offwhite transition">
                    <div class="h-8 w-8 rounded-full bg-gold flex items-center justify-center font-bold text-dark-brown">
                        <?php echo e(strtoupper(substr(auth()->user()->full_name ?? auth()->user()->name ?? 'G', 0, 1))); ?>

                    </div>
                    <span class="hidden sm:inline"><?php echo e(auth()->user()->full_name ?? auth()->user()->name ?? 'Guide'); ?></span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" x-transition class="absolute right-0 top-full mt-2 w-64 rounded-xl bg-offwhite border border-gold shadow-2xl py-2 z-50">
                    <a href="<?php echo e(route('profile.edit')); ?>" class="block px-4 py-3 text-sm text-dark-brown hover:bg-cream">My Profile</a>
                    <a href="#" class="block px-4 py-3 text-sm text-dark-brown hover:bg-cream">Analytics</a>
                    <a href="#" class="block px-4 py-3 text-sm text-dark-brown hover:bg-cream">Payouts</a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full px-4 py-3 text-left text-sm text-dark-brown hover:bg-cream">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-20 pb-24">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Earnings Summary -->
            <section class="mb-12">
                <h1 class="mb-8 font-asimovian text-3xl font-bold text-dark-brown">Dashboard</h1>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Total Earnings -->
                    <div class="group rounded-2xl bg-cream p-8 shadow-lg hover:shadow-2xl transition-all border border-olive/20 hover:border-gold">
                        <div class="flex items-center justify-between mb-4">
                            <div class="h-12 w-12 rounded-xl bg-gold flex items-center justify-center shadow-lg">
                                <svg class="h-6 w-6 text-dark-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="rounded-full bg-olive/20 px-3 py-1 text-xs font-semibold text-olive uppercase tracking-wide">All Time</span>
                        </div>
                        <p class="text-3xl font-bold text-dark-brown mb-1">₱148,500</p>
                        <p class="text-sm text-dark-brown/60">Total Revenue</p>
                    </div>

                    <!-- Completed Bookings -->
                    <div class="group rounded-2xl bg-cream p-8 shadow-lg hover:shadow-2xl transition-all border border-olive/20 hover:border-gold">
                        <div class="flex items-center justify-between mb-4">
                            <div class="h-12 w-12 rounded-xl bg-gold flex items-center justify-center shadow-lg">
                                <svg class="h-6 w-6 text-dark-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="rounded-full bg-olive/20 px-3 py-1 text-xs font-semibold text-olive uppercase tracking-wide">Completed</span>
                        </div>
                        <p class="text-3xl font-bold text-dark-brown mb-1">47</p>
                        <p class="text-sm text-dark-brown/60">Bookings</p>
                    </div>

                    <!-- Average Rating -->
                    <div class="group rounded-2xl bg-cream p-8 shadow-lg hover:shadow-2xl transition-all border border-olive/20 hover:border-gold">
                        <div class="flex items-center justify-between mb-4">
                            <div class="h-12 w-12 rounded-xl bg-gold flex items-center justify-center shadow-lg">
                                <svg class="h-6 w-6 text-dark-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </div>
                            <span class="rounded-full bg-olive/20 px-3 py-1 text-xs font-semibold text-olive uppercase tracking-wide">Average</span>
                        </div>
                        <p class="text-3xl font-bold text-dark-brown mb-1">4.8</p>
                        <p class="text-sm text-dark-brown/60">Rating</p>
                        <div class="flex gap-1 mt-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?>
                                <svg class="h-4 w-4 text-gold fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Recent Selected Bookings -->
            <section class="mb-12">
                <h2 class="mb-6 font-playfair text-2xl font-bold text-dark-brown">Recent Selected Bookings</h2>
                <div class="rounded-2xl bg-cream p-6 shadow-lg border border-olive/20">
                    <div class="overflow-hidden">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gold/30">
                                    <th class="text-left py-3 text-sm font-semibold text-dark-brown uppercase tracking-wide">Tourist</th>
                                    <th class="text-left py-3 text-sm font-semibold text-dark-brown uppercase tracking-wide">Tour</th>
                                    <th class="text-left py-3 text-sm font-semibold text-dark-brown uppercase tracking-wide">Date</th>
                                    <th class="text-left py-3 text-sm font-semibold text-dark-brown uppercase tracking-wide">Revenue</th>
                                    <th class="text-left py-3 text-sm font-semibold text-dark-brown uppercase tracking-wide">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gold/20">
                                <tr class="hover:bg-offwhite/50 transition">
                                    <td class="py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-gold flex items-center justify-center font-bold text-dark-brown">MT</div>
                                            <div>
                                                <p class="font-semibold text-dark-brown">Mia Tan</p>
                                                <p class="text-xs text-dark-brown/60">mia@example.com</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <p class="font-semibold text-dark-brown">Boracay Beach Day</p>
                                    </td>
                                    <td class="py-4">
                                        <p class="text-sm text-dark-brown">Apr 28, 2026</p>
                                    </td>
                                    <td class="py-4">
                                        <p class="font-bold text-olive">₱8,000</p>
                                    </td>
                                    <td class="py-4">
                                        <span class="bg-olive/10 text-olive rounded-full px-3 py-1 text-xs font-semibold">Completed</span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-offwhite/50 transition">
                                    <td class="py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-gold flex items-center justify-center font-bold text-dark-brown">NL</div>
                                            <div>
                                                <p class="font-semibold text-dark-brown">Noah Lim</p>
                                                <p class="text-xs text-dark-brown/60">noah@example.com</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4">
                                        <p class="font-semibold text-dark-brown">Siargao Surf Trip</p>
                                    </td>
                                    <td class="py-4">
                                        <p class="text-sm text-dark-brown">May 3, 2026</p>
                                    </td>
                                    <td class="py-4">
                                        <p class="font-bold text-olive">₱12,000</p>
                                    </td>
                                    <td class="py-4">
                                        <span class="bg-olive-light/10 text-olive-light rounded-full px-3 py-1 text-xs font-semibold">Pending</span>
                                    </td>
                                </tr>
                                <!-- More rows... -->
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6 text-center">
                        <a href="#" class="inline-flex items-center gap-2 rounded-lg bg-gold px-6 py-3 text-sm font-semibold text-dark-brown hover:bg-gold-dark shadow-lg transition">
                            View All Bookings
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </section>

            <!-- Metrics & Recent Comments -->
            <section class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                <!-- Performance Metrics -->
                <div class="rounded-2xl bg-cream p-8 shadow-lg border border-olive/20">
                    <h3 class="mb-6 font-playfair text-xl font-bold text-dark-brown">Performance Metrics</h3>
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-sm font-semibold text-dark-brown/70 uppercase tracking-wide mb-2">Response Time</p>
                            <p class="text-3xl font-bold text-olive mb-1">2.4h</p>
                            <div class="w-full bg-dark-brown/10 rounded-full h-3">
                                <div class="bg-olive h-3 rounded-full" style="width: 78%"></div>
                            </div>
                            <p class="text-xs text-dark-brown/60 mt-1">Avg response to inquiries</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-dark-brown/70 uppercase tracking-wide mb-2">Selection Rate</p>
                            <p class="text-3xl font-bold text-gold mb-1">86%</p>
                            <div class="w-full bg-dark-brown/10 rounded-full h-3">
                                <div class="bg-gold h-3 rounded-full" style="width: 86%"></div>
                            </div>
                            <p class="text-xs text-dark-brown/60 mt-1">Bookings won from requests</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Comments -->
                <div class="rounded-2xl bg-cream p-8 shadow-lg border border-olive/20">
                    <h3 class="mb-6 font-playfair text-xl font-bold text-dark-brown">Recent Comments</h3>
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="h-10 w-10 rounded-full bg-gold flex items-center justify-center font-bold text-dark-brown text-sm">MT</div>
                            <div class="flex-grow">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-semibold text-dark-brown">Mia Tan</span>
                                    <span class="text-xs text-dark-brown/60">2h ago</span>
                                </div>
                                <p class="text-sm text-dark-brown">"Excellent guide! Highly recommend for Batad trek."</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <div class="h-10 w-10 rounded-full bg-gold flex items-center justify-center font-bold text-dark-brown text-sm">NL</div>
                            <div class="flex-grow">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-semibold text-dark-brown">Noah Lim</span>
                                    <span class="text-xs text-dark-brown/60">1d ago</span>
                                </div>
                                <p class="text-sm text-dark-brown">"Great communication and safe surfing lesson."</p>
                            </div>
                        </div>
                        <!-- More... -->
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
<?php /**PATH /home/mistah-regime/tribaltours/resources/views/dashboards/guide.blade.php ENDPATH**/ ?>