<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrblTours - Tourist Feed</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;500;600&family=Asimovian:wght@400;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .nav-shell {
            background: rgba(92, 64, 51, 0.94);
            backdrop-filter: blur(10px);
            transition: all 0.28s ease;
        }
        .nav-shell.scrolled {
            background: rgba(212, 165, 99, 0.88);
            box-shadow: 0 10px 30px -16px rgba(51, 36, 24, 0.55);
        }
        .filter-shell {
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="min-h-screen bg-[#fffaf3] text-[#3e2a1f] font-inter">
    @php
        $feedItems = collect($posts?->items() ?? []);
        $currentUser = auth()->user();
        $isTourist = $currentUser && $currentUser->role === 'tourist';
    @endphp

    <div x-data="{ scrolled: false, menuOpen: false }" x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 32; })">
        <!-- Sticky Navbar -->
        <header :class="scrolled ? 'nav-shell scrolled' : 'nav-shell'" class="fixed inset-x-0 top-0 z-50 border-b border-[#d4a563]/25">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                <a href="{{ route('dashboard.tourist') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/tribaltours_icon.png') }}" alt="TrblTours Icon" class="h-10 w-10 rounded-full border border-[#d4a563]/60 bg-[#f6ecd8] object-cover shadow-md">
                    <span class="font-[Asimovian] text-2xl font-bold tracking-wide text-[#f8eed8] drop-shadow">TrblTours</span>
                </a>

                <div class="relative" @click.outside="menuOpen = false">
                    <button @click="menuOpen = !menuOpen" class="inline-flex items-center gap-2 rounded-full border border-[#e8d2ab] bg-[#fff6e8] px-4 py-2 text-sm font-semibold text-[#5a3c2a] transition hover:border-[#d4a563] hover:bg-[#f7e8cf]">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#8f9d59] text-xs font-bold text-white">
                            {{ strtoupper(substr($currentUser->full_name ?? $currentUser->name ?? 'T', 0, 1)) }}
                        </span>
                        <span class="hidden sm:inline">{{ $currentUser->full_name ?? $currentUser->name ?? 'Tourist' }}</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="menuOpen" x-transition class="absolute right-0 mt-2 w-64 overflow-hidden rounded-2xl border border-[#d4a563]/40 bg-[#fffaf3] py-2 shadow-2xl">
                        <a href="#" class="block px-4 py-2.5 text-sm text-[#5a3c2a] transition hover:bg-[#f7ecd7]">Messages</a>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-[#5a3c2a] transition hover:bg-[#f7ecd7]">Profile Dashboard (edit profile)</a>
                        <a href="#" class="block px-4 py-2.5 text-sm text-[#5a3c2a] transition hover:bg-[#f7ecd7]">My Posts</a>
                        <a href="#" class="block px-4 py-2.5 text-sm text-[#5a3c2a] transition hover:bg-[#f7ecd7]">My Bookings and Rate</a>
                        <a href="#" class="block px-4 py-2.5 text-sm text-[#5a3c2a] transition hover:bg-[#f7ecd7]">Notifications</a>
                        <a href="#" class="block px-4 py-2.5 text-sm text-[#5a3c2a] transition hover:bg-[#f7ecd7]">Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2.5 text-left text-sm text-[#5a3c2a] transition hover:bg-[#f7ecd7]">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Sticky Filter Bar -->
        <section class="filter-shell sticky top-[72px] z-40 border-b border-[#d4a563]/25 bg-[#fffaf3]/95">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <form method="GET" action="{{ route('dashboard.tourist') }}" class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label for="location" class="mb-1 block text-sm font-semibold text-[#6b4a34]">Location</label>
                        <select id="location" name="location" class="w-full rounded-xl border border-[#d6bc93] bg-white text-sm text-[#5a3c2a] shadow-sm focus:border-[#8f9d59] focus:ring-[#8f9d59]/30">
                            <option value="">All Regions</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region }}" @selected($location === $region)>{{ $region }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="sort_by" class="mb-1 block text-sm font-semibold text-[#6b4a34]">Sort By</label>
                        <select id="sort_by" name="sort_by" class="w-full rounded-xl border border-[#d6bc93] bg-white text-sm text-[#5a3c2a] shadow-sm focus:border-[#8f9d59] focus:ring-[#8f9d59]/30">
                            <option value="latest" @selected($sortBy === 'latest')>Latest</option>
                            <option value="price_low_high" @selected($sortBy === 'price_low_high')>Price Low to High</option>
                            <option value="price_high_low" @selected($sortBy === 'price_high_low')>Price High to Low</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full rounded-xl border border-[#c9a26a] bg-[#d4a563] px-4 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-[#bf9155] hover:shadow-lg">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Main Content -->
        <main class="mx-auto max-w-7xl px-4 pb-24 pt-8 sm:px-6 lg:px-8">
            <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($feedItems as $item)
                    @php
                        $type = $item['type'] ?? null;
                        $record = $item['data'] ?? null;
                        $guide = $record?->marketplaceGuide;
                    @endphp

                    @if ($type === 'tour_listing' && $record)
                        <article class="group flex h-full flex-col rounded-2xl border border-[#e2cda9] bg-white p-6 shadow-[0_14px_36px_-24px_rgba(58,39,26,0.55)] transition duration-300 hover:-translate-y-0.5 hover:border-[#d4a563] hover:shadow-[0_18px_44px_-22px_rgba(58,39,26,0.7)]">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-xs font-semibold uppercase tracking-wider text-[#8f9d59]">Verified Guide Listing</p>
                                <span class="inline-flex items-center gap-1 rounded-full border border-[#d4a563]/60 bg-[#fff3de] px-2.5 py-1 text-[11px] font-semibold text-[#7a5532]">
                                    <svg class="h-3.5 w-3.5 text-[#d4a563]" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.172 7.707 8.879a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Verified
                                </span>
                            </div>

                            <p class="mt-3 text-sm text-[#6b4a34]">
                                Guide:
                                <span class="font-semibold text-[#4b3224]">{{ $guide->full_name ?? $guide->name ?? 'Verified Guide' }}</span>
                            </p>

                            <h2 class="mt-2 text-xl font-bold text-[#3e2a1f]">{{ $record->title ?? $record->name ?? 'Untitled Tour' }}</h2>
                            <p class="mt-2 text-sm text-[#73543e]">Location: {{ $record->region ?? 'Region not specified' }}</p>
                            <p class="mt-1 text-sm text-[#73543e]">Duration: {{ $record->duration_label ?? 'Flexible duration' }}</p>
                            <p class="mt-4 text-2xl font-extrabold text-[#3e2a1f]">PHP {{ number_format((float) ($record->price_per_person ?? $record->price ?? 0), 0) }}</p>

                            <div class="mt-6">
                                <button type="button" class="inline-flex items-center justify-center rounded-xl border border-[#7e8c4e] bg-[#8f9d59] px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:border-[#d4a563] hover:bg-[#7f8d4d] hover:shadow-lg">
                                    Book Now
                                </button>
                            </div>
                        </article>
                    @elseif ($type === 'request_post' && $record)
                        <article class="flex h-full flex-col rounded-2xl border border-[#e2cda9] bg-white p-6 shadow-[0_14px_36px_-24px_rgba(58,39,26,0.55)] transition duration-300 hover:border-[#d4a563] hover:shadow-[0_18px_44px_-22px_rgba(58,39,26,0.68)]">
                            <p class="text-xs font-semibold uppercase tracking-wider text-[#a17649]">Request Post</p>
                            <h2 class="mt-2 text-lg font-bold text-[#3e2a1f]">{{ $record->tour?->title ?? 'Custom Tour Request' }}</h2>
                            <p class="mt-1 text-sm text-[#73543e]">Location: {{ $record->tour?->region ?? 'Region not specified' }}</p>
                            <p class="mt-2 text-sm text-[#73543e]">Group Size: {{ $record->group_size ?? 'N/A' }}</p>
                            <p class="mt-2 text-xl font-bold text-[#3e2a1f]">PHP {{ number_format((float) ($record->total_price ?? 0), 0) }}</p>
                            <div class="mt-6">
                                <button type="button" class="rounded-xl border border-[#c9a26a] bg-[#d4a563] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#bf9155]">
                                    View Details
                                </button>
                            </div>
                        </article>
                    @endif
                @empty
                    <article class="col-span-full rounded-2xl border border-dashed border-[#d9be96] bg-white p-8 text-center text-[#73543e] shadow-sm">
                        No posts found for the current filters.
                    </article>
                @endforelse
            </section>

            @if (method_exists($posts, 'links'))
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @endif
        </main>

        <!-- Floating Tourist Action Button -->
        @if ($isTourist)
            <div class="fixed bottom-6 right-6 z-50">
                <a href="{{ route('requests.store') }}"
                   onclick="event.preventDefault();"
                   class="inline-flex items-center gap-2 rounded-full border border-[#d4a563] bg-[#8f9d59] px-5 py-3 text-sm font-semibold text-white shadow-[0_14px_28px_-14px_rgba(51,36,24,0.78)] transition hover:-translate-y-0.5 hover:bg-[#7f8d4d] hover:shadow-[0_18px_36px_-14px_rgba(51,36,24,0.88)]">
                    <svg class="h-4 w-4 text-[#f4d9aa]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create Request Post
                </a>
            </div>
        @endif
    </div>
</body>
</html>
