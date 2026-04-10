@php
    $tourPreviews = $tours->map(function ($tour) {
        $rawImages = [];
        $transportationItems = [];
        $tourAttributes = (array) $tour->getAttributes();

        if (is_array($tour->activities)) {
            $transportationItems = collect($tour->activities)
                ->filter(fn (mixed $value): bool => is_string($value) && trim($value) !== '')
                ->values()
                ->all();
        } elseif (is_string($tour->activities) && trim($tour->activities) !== '') {
            $decodedActivities = json_decode($tour->activities, true);

            if (is_array($decodedActivities) && count($decodedActivities) > 0) {
                $transportationItems = collect($decodedActivities)
                    ->filter(fn (mixed $value): bool => is_string($value) && trim($value) !== '')
                    ->values()
                    ->all();
            }
        }

        // Always fallback to category if activities is empty or missing
        if ($transportationItems === [] && is_string($tour->category) && trim($tour->category) !== '') {
            $transportationItems = collect(explode(',', $tour->category))
                ->map(fn (string $item): string => trim($item))
                ->map(fn (string $item): string => strtolower(str_replace(' ', '_', $item)))
                ->filter(fn (string $item): bool => $item !== '' && in_array($item, ['private_transportation', 'public_transportation', 'walking_tour', 'boat_bangka'], true))
                ->values()
                ->all();
        }

            $city = collect(['city', 'city_municipality', 'municipality', 'location'])
                ->map(fn (string $key): string => (string) ($tourAttributes[$key] ?? ''))
                ->first(fn (string $value): bool => trim($value) !== '') ?: 'Not specified';

            $durationLabel = (string) ($tour->duration_label ?? $tour->duration ?? '');
            $durationHours = $tour->duration_hours;
            if ($durationHours === null || $durationHours === '') {
                $durationHours = preg_match('/\d+/', $durationLabel, $durationMatches) === 1 ? (int) $durationMatches[0] : null;
            }

            $durationUnit = (string) ($tour->duration_unit ?? '');
            if ($durationUnit === '') {
                $durationUnit = str_contains(strtolower($durationLabel), 'day') ? 'days' : 'hours';
            }

            $minGuests = (int) ($tour->min_guests ?? 0);
            $maxGuests = (int) ($tour->max_guests ?? 0);
            if ($minGuests === 0 && $maxGuests === 0) {
                $groupSize = collect(['group_size', 'max_people'])
                    ->map(fn (string $key): string => (string) ($tourAttributes[$key] ?? ''))
                    ->first(fn (string $value): bool => trim($value) !== '');

                if ($groupSize !== '') {
                    $minGuests = (int) $groupSize;
                    $maxGuests = (int) $groupSize;
                }
            }

            $availableOn = $tour->available_on;
            if ($availableOn instanceof DateTimeInterface) {
                $availableOn = $availableOn->format('M d, Y');
            } elseif (is_string($availableOn) && trim($availableOn) !== '') {
                $availableOn = substr(trim($availableOn), 0, 10);
            } else {
                $availableOn = 'Not specified';
            }

        foreach ([$tour->featured_image, $tour->image_url, $tour->image_path] as $image) {
            if (is_string($image) && trim($image) !== '') {
                $rawImages[] = trim($image);
            }
        }

        if (is_array($tour->gallery_images)) {
            foreach ($tour->gallery_images as $galleryImage) {
                if (is_string($galleryImage) && trim($galleryImage) !== '') {
                    $rawImages[] = trim($galleryImage);
                }
            }
        }

        $images = collect($rawImages)
            ->map(fn (string $path) => Illuminate\Support\Str::startsWith($path, ['http://', 'https://']) ? $path : asset('storage/'.$path))
            ->unique()
            ->take(3)
            ->values()
            ->all();

        $transportationSummary = collect($transportationItems)
            ->map(fn (string $item): string => str_replace('_', ' ', ucwords($item, '_')))
            ->implode(', ');

        return [
            'id' => $tour->id,
            'title' => (string) ($tour->name ?? $tour->title ?? 'Untitled tour'),
            'description' => (string) ($tour->description ?? $tour->short_description ?? $tour->summary ?? 'No full description provided.'),
            'summary' => (string) ($tour->short_description ?? $tour->summary ?? ''),
            'generated_summary' => implode('<br>', array_values(array_filter([
                (($tour->price ?? $tour->price_per_person ?? 0) > 0)
                    ? 'Php '.number_format((float) ($tour->price ?? $tour->price_per_person), 2).' per '.((string) ($tour->price_unit ?? 'person'))
                    : null,
                $transportationItems !== [] ? 'Transportation: '.$transportationSummary : null,
                $availableOn !== 'Not specified' ? 'Date: '.$availableOn : null,
                ($minGuests > 0 || $maxGuests > 0)
                    ? (($minGuests ?: 1).' - '.($maxGuests ?: ($minGuests ?: 1)).' guests')
                    : null,
            ]))),
            'price' => (float) ($tour->price ?? $tour->price_per_person ?? 0),
            'price_unit' => (string) ($tour->price_unit ?? 'person'),
            'duration' => $durationHours !== null ? $durationHours.' '.(($durationHours === 1) ? rtrim($durationUnit, 's') : $durationUnit) : ($durationLabel !== '' ? $durationLabel : 'Not specified'),
            'transportation' => $transportationItems,
            'location' => $city !== 'Not specified' ? $city : (string) ($tour->region ?? 'Not specified'),
            'region' => (string) ($tour->region ?? 'Not specified'),
            'available_on' => $availableOn,
            'status' => (string) ($tour->status ?? 'draft'),
            'min_guests' => $minGuests,
            'max_guests' => $maxGuests,
            'images' => $images,
        ];
    })->values();
@endphp

<div
    class="min-h-screen bg-white pb-10"
    x-data="{
        activeTab: 'posts',
        tourPreviews: @js($tourPreviews),
        selectedTour: null,
        openTourPreview(tourId) {
            this.selectedTour = this.tourPreviews.find((tour) => Number(tour.id) === Number(tourId)) ?? null;
        },
        closeTourPreview() {
            this.selectedTour = null;
        }
    }"
    @keydown.escape.window="closeTourPreview()"
>
    <section class="mx-auto w-full max-w-6xl px-4 pt-6 sm:px-6 lg:px-8">
        <div class="mb-4 rounded-lg border border-[#d4a563] bg-white px-6 py-4 shadow-sm">
            <h2 class="text-xl font-semibold leading-tight text-slate-900">{{ __('Your Guide Profile') }}</h2>
            <p class="mt-1 text-sm text-slate-500">Your public profile preview with your stories and tours.</p>
        </div>

        <article class="overflow-hidden rounded-lg border border-[#d4a563] bg-white shadow-md">
            <div class="relative h-56 w-full bg-gradient-to-r from-[#7a8f3a] to-[#556b2f]">
                @if ($guide['cover_photo_path'] !== '')
                    <img src="{{ asset('storage/'.$guide['cover_photo_path']) }}" alt="Guide cover photo" class="h-full w-full object-cover">
                @endif
            </div>

            <div class="relative px-6 pb-6 pt-16 sm:px-8">
                <div class="absolute -top-14 left-6 sm:left-8">
                    <div class="h-28 w-28 overflow-hidden rounded-full border-4 border-white bg-[#eef2df] shadow-lg ring-2 ring-[#d4a563]">
                        @if ($guide['profile_photo_path'] !== '')
                            <img src="{{ asset('storage/'.$guide['profile_photo_path']) }}" alt="Guide profile photo" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-[#7a8f3a] to-[#556b2f] text-3xl font-bold text-white">
                                {{ strtoupper(substr($guide['display_name'], 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3">
                            <h3 class="text-3xl font-bold text-slate-900">{{ $guide['display_name'] }}</h3>
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold uppercase tracking-wide {{ $verificationStatus === 'approved' ? 'bg-[#eef2df] text-[#556b2f]' : 'bg-amber-100 text-amber-700' }}">
                                {{ $verificationStatus === 'approved' ? 'Verified' : 'Pending' }}
                            </span>
                        </div>

                        <p class="mt-2 text-sm text-slate-600">
                            {{ $guide['region'] ?: 'Region not set' }}
                            @if ($guide['city_municipality'] !== '')
                                • {{ $guide['city_municipality'] }}
                            @endif
                            @if ($guide['barangay'] !== '')
                                • {{ $guide['barangay'] }}
                            @endif
                        </p>

                        @if ($guide['bio'] !== '')
                            <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-700">{{ $guide['bio'] }}</p>
                        @endif
                    </div>

                    <div class="flex flex-col gap-2 sm:w-auto">
                        <a href="{{ route('dashboard.guide.profile.edit') }}" class="inline-flex items-center justify-center rounded-lg border border-[#d4a563] bg-white px-4 py-2 text-sm font-semibold text-[#7a5532] transition hover:bg-[#fff7ec]">
                            Edit Profile
                        </a>
                        <a href="{{ route('dashboard.guide') }}" class="inline-flex items-center justify-center rounded-lg border border-[#d4a563] bg-[#d4a563] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#bf9155]">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </article>

        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <article class="rounded-lg border border-[#d4a563] bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Completed Tours</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['totalToursCompleted'] }}</p>
            </article>

            <article class="rounded-lg border border-[#d4a563] bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Average Rating</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($stats['averageRating'], 1) }}/5</p>
                <p class="mt-1 text-xs text-slate-500">{{ $stats['totalReviews'] }} total reviews</p>
            </article>

            <article class="rounded-lg border border-[#d4a563] bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Experience</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $guide['years_of_experience'] !== '' ? $guide['years_of_experience'] : '0' }}</p>
                <p class="mt-1 text-xs text-slate-500">years guiding travelers</p>
            </article>
        </div>

        <div class="mt-6 overflow-hidden rounded-lg border border-[#d4a563] bg-white shadow-sm">
            <div class="border-b border-[#ead2ad] px-6">
                <div class="flex gap-6">
                    <button type="button" @click="activeTab = 'posts'" :class="activeTab === 'posts' ? 'border-[#d4a563] text-[#7a5532]' : 'border-transparent text-slate-500'" class="border-b-2 px-1 py-4 text-sm font-semibold transition">
                        My Posts ({{ $posts->count() }})
                    </button>
                    <button type="button" @click="activeTab = 'tours'" :class="activeTab === 'tours' ? 'border-[#d4a563] text-[#7a5532]' : 'border-transparent text-slate-500'" class="border-b-2 px-1 py-4 text-sm font-semibold transition">
                        My Tours ({{ $tours->count() }})
                    </button>
                </div>
            </div>

            <div class="p-6" x-show="activeTab === 'posts'" x-cloak>
                @if ($posts->isNotEmpty())
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($posts as $post)
                            @php
                                $postImagePath = (string) ($post->image_path ?? '');
                                $postImageSrc = Illuminate\Support\Str::startsWith($postImagePath, ['http://', 'https://'])
                                    ? $postImagePath
                                    : asset('storage/'.$postImagePath);
                            @endphp

                            <article class="overflow-hidden rounded-[18px] border border-[#e9d7b6] bg-white shadow-sm transition hover:shadow-md" role="presentation">
                                <div class="h-44 w-full bg-[#eef2df]">
                                    @if ($postImagePath !== '')
                                        <img src="{{ $postImageSrc }}" alt="Guide story image" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-sm font-medium text-[#556b2f]">No image</div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <p class="text-sm leading-6 text-slate-700">{{ $post->caption ?: 'No caption provided.' }}</p>
                                    <p class="mt-3 text-xs text-slate-500">{{ $post->created_at?->diffForHumans() }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-lg border border-dashed border-[#d4a563] bg-[#f4f6eb] p-8 text-center">
                        <p class="text-sm text-slate-600">You do not have any posts yet.</p>
                    </div>
                @endif
            </div>

            <div class="p-6" x-show="activeTab === 'tours'" x-cloak>
                @if ($tours->isNotEmpty())
                    <div class="grid gap-6 lg:grid-cols-2">
                        @foreach ($tours as $tour)
                            <article class="overflow-hidden rounded-lg border border-[#e2c08c] bg-white shadow-sm">
                                <div class="h-44 w-full bg-[#eef2df]">
                                    @if (! empty($tour->featured_image) || ! empty($tour->image_url) || ! empty($tour->image_path))
                                        @php
                                            $tourImagePath = (string) ($tour->featured_image ?? $tour->image_url ?? $tour->image_path);
                                            $tourImageSrc = Illuminate\Support\Str::startsWith($tourImagePath, ['http://', 'https://'])
                                                ? $tourImagePath
                                                : asset('storage/'.$tourImagePath);
                                        @endphp
                                        <img src="{{ $tourImageSrc }}" alt="Tour image" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-sm font-medium text-[#556b2f]">No image</div>
                                    @endif
                                </div>

                                <div class="p-5">
                                    <div class="flex items-start justify-between gap-4">
                                        <h4 class="text-lg font-semibold text-slate-900">{{ $tour->name ?? $tour->title ?? 'Untitled tour' }}</h4>
                                        <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide {{ ($tourPreviews->firstWhere('id', $tour->id)['status'] ?? 'draft') === 'active' ? 'bg-[#eef2df] text-[#556b2f]' : 'bg-slate-100 text-slate-600' }}">
                                            {{ str_replace('_', ' ', ucfirst($tourPreviews->firstWhere('id', $tour->id)['status'] ?? 'draft')) }}
                                        </span>
                                    </div>

                                    <p class="mt-2 text-sm leading-6 text-slate-600">
                                        {{ $tourPreviews->firstWhere('id', $tour->id)['location'] ?? $tour->region ?: 'Location not set' }}
                                    </p>

                                    <div class="mt-4 rounded-[18px] border border-[#ead8bb] bg-[#fffaf2] px-4 py-3">
                                        <p class="mt-2 text-sm text-slate-700">Price: PHP {{ number_format((float) ($tour->price ?? $tour->price_per_person ?? 0), 2) }} per {{ $tour->price_unit ?? 'person' }}</p>
                                        <p class="mt-2 text-sm text-slate-700">Group: {{ ($tourPreviews->firstWhere('id', $tour->id)['min_guests'] ?? 1) }} - {{ ($tourPreviews->firstWhere('id', $tour->id)['max_guests'] ?? ($tourPreviews->firstWhere('id', $tour->id)['min_guests'] ?? 1)) }} guests</p>
                                        <p class="mt-2 text-sm text-slate-700">Date: {{ $tourPreviews->firstWhere('id', $tour->id)['available_on'] ?? 'Date not set' }}</p>
                                    </div>

                                    <div class="mt-4 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                        @if (! empty($tourPreviews->firstWhere('id', $tour->id)['duration']))
                                            <span class="rounded-full bg-slate-100 px-2.5 py-1">{{ $tourPreviews->firstWhere('id', $tour->id)['duration'] }}</span>
                                        @endif
                                        @foreach (($tourPreviews->firstWhere('id', $tour->id)['transportation'] ?? []) as $transportationLabel)
                                            <span class="rounded-full bg-[#eef2df] px-2.5 py-1 font-medium text-[#556b2f]">{{ str_replace('_', ' ', ucwords($transportationLabel, '_')) }}</span>
                                        @endforeach
                                    </div>

                                    <button type="button" class="mt-4 inline-flex rounded-lg border border-[#d4a563] bg-white px-3 py-2 text-xs font-semibold text-[#7a5532] transition hover:bg-[#fff7ec]" @click.stop="openTourPreview({{ $tour->id }})">
                                        Live Preview
                                    </button>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-lg border border-dashed border-[#d4a563] bg-[#f4f6eb] p-8 text-center">
                        <p class="text-sm text-slate-600">You do not have any tours yet.</p>
                        <a href="{{ route('dashboard.guide.tours') }}" class="mt-4 inline-flex rounded-lg bg-[#556b2f] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#465826]">
                            Create Tour
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <div x-show="selectedTour" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4" @click.self="closeTourPreview()">
        <div class="max-h-[90vh] w-full max-w-4xl overflow-y-auto rounded-xl border border-[#d4a563] bg-white shadow-2xl">
            <div class="sticky top-0 z-10 flex items-center justify-between border-b border-[#ead2ad] bg-white px-6 py-4">
                <h3 class="text-lg font-bold text-slate-900" x-text="selectedTour?.title"></h3>
                <button type="button" class="rounded-lg border border-[#d4a563] px-3 py-1 text-sm font-semibold text-[#7a5532] transition hover:bg-[#fff7ec]" @click="closeTourPreview()">
                    Close
                </button>
            </div>

            <div class="space-y-6 p-6">
                <div class="grid gap-4 md:grid-cols-3">
                    <template x-for="(image, imageIndex) in (selectedTour?.images ?? []).slice(0, 3)" :key="imageIndex">
                        <div class="h-48 overflow-hidden rounded-lg border border-[#e2c08c] bg-[#f4f6eb]">
                            <img :src="image" alt="Tour preview image" class="h-full w-full object-cover">
                        </div>
                    </template>
                    <template x-if="(selectedTour?.images ?? []).length === 0">
                        <div class="md:col-span-3 flex h-48 items-center justify-center rounded-lg border border-dashed border-[#d4a563] bg-[#f4f6eb] text-sm text-slate-600">
                            No images available
                        </div>
                    </template>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="rounded-lg border border-[#e2c08c] p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Price</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900" x-text="selectedTour?.price > 0 ? `Php ${Number(selectedTour.price).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} per ${selectedTour.price_unit}` : 'Not specified'"></p>
                    </div>
                    <div class="rounded-lg border border-[#e2c08c] p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Duration</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900" x-text="selectedTour?.duration"></p>
                    </div>
                    <div class="rounded-lg border border-[#e2c08c] p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Transportation</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900" x-show="Array.isArray(selectedTour?.transportation) && selectedTour.transportation.length > 0" x-text="selectedTour?.transportation.map((item) => item.replaceAll('_', ' ').replace(/\b\w/g, (character) => character.toUpperCase())).join(', ')"></p>
                        <p class="mt-1 text-sm font-semibold text-slate-900" x-show="!Array.isArray(selectedTour?.transportation) || selectedTour.transportation.length === 0" x-text="selectedTour?.transportation || 'Not specified'"></p>
                    </div>
                    <div class="rounded-lg border border-[#e2c08c] p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Date</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900" x-text="selectedTour?.available_on"></p>
                    </div>
                    <div class="rounded-lg border border-[#e2c08c] p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Status</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900" x-text="selectedTour?.status ? selectedTour.status.replaceAll('_', ' ').replace(/\b\w/g, (character) => character.toUpperCase()) : 'Draft'"></p>
                    </div>
                    <div class="rounded-lg border border-[#e2c08c] p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Location</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900" x-text="selectedTour?.location"></p>
                    </div>
                    <div class="rounded-lg border border-[#e2c08c] p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Guest Range</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900" x-text="`${selectedTour?.min_guests || 1} - ${selectedTour?.max_guests || selectedTour?.min_guests || 1} guests`"></p>
                    </div>
                </div>

                <div class="rounded-lg border border-[#e2c08c] p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Summary</p>
                    <p class="mt-2 text-sm leading-6 text-slate-700" x-show="selectedTour?.generated_summary" x-html="selectedTour?.generated_summary"></p>
                    <p class="mt-2 text-sm leading-6 text-slate-700" x-show="!selectedTour?.generated_summary" x-text="selectedTour?.summary || 'Not specified'"></p>
                </div>

                <div class="rounded-lg border border-[#e2c08c] p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Full Description</p>
                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700" x-text="selectedTour?.description"></p>
                </div>
            </div>
        </div>
    </div>
</div>
