<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900">
            {{ __('Guide Dashboard') }}
        </h2>
    </x-slot>

    <div class="bg-gradient-to-b from-[#fff8ef] via-[#fffdf9] to-[#f7ead7] py-10">
        <div class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 sm:px-6 lg:px-8">
            <section class="rounded-2xl border border-[#d4a563]/35 bg-white p-6 shadow-[0_16px_34px_-20px_rgba(122,85,50,0.6)] sm:p-8">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                    <div class="space-y-2">
                        <p class="text-sm font-medium uppercase tracking-wide text-[#8a6746]">Guide Home</p>
                        <h3 class="text-2xl font-semibold text-slate-900">Welcome back, {{ $firstName }}!</h3>
                        <p class="text-sm text-slate-600">Keep your guide profile complete so travelers can confidently book your tours.</p>
                    </div>

                    <div class="min-w-[180px] rounded-xl border border-[#d4a563]/30 bg-[#fff7ec] px-4 py-3">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Profile Completion</p>
                        <p class="mt-1 text-3xl font-bold text-slate-900">{{ $completionPercentage }}%</p>
                    </div>
                </div>

                <div class="mt-6 rounded-xl border border-[#d4a563]/30 bg-[#fff7ec] p-4">
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <p class="text-sm font-semibold text-slate-800">Completion Progress</p>
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $progressTheme['badge'] }}">{{ $progressTheme['label'] }}</span>
                    </div>
                    <div class="h-3 w-full overflow-hidden rounded-full bg-[#f0dfc4]">
                        <div class="h-full {{ $progressTheme['bar'] }}" style="width: {{ $completionPercentage }}%"></div>
                    </div>
                </div>

                @if ($showCompletionReminder)
                    <div class="mt-4 rounded-xl border border-[#d4a563]/45 bg-[#fff5e6] px-4 py-3 text-sm font-medium text-[#7a5532]">
                        Complete your profile to start accepting bookings!
                    </div>
                @endif

                @if ($showVerificationNotice)
                    <div class="mt-3 rounded-xl border border-[#d4a563]/45 bg-[#fff8ef] px-4 py-3 text-sm font-medium text-[#7a5532]">
                        Your documents are under review. We'll notify you within 24-48 hours.
                    </div>
                @endif

            </section>

            <section class="rounded-2xl border border-[#d4a563]/35 bg-white p-6 shadow-[0_12px_26px_-16px_rgba(122,85,50,0.55)]">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <h3 class="text-lg font-semibold text-slate-900">Profile Completion Checklist</h3>
                    <span class="text-sm font-medium text-slate-500">{{ $completionPercentage }}% complete</span>
                </div>

                <div class="grid gap-3 md:grid-cols-2">
                    @foreach ($criteria as $item)
                        <article class="rounded-xl border p-4 {{ $item['complete'] ? 'border-[#d4a563]/45 bg-[#fff5e6]' : 'border-[#ecd3ad] bg-[#fffaf2]' }}">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $item['label'] }}</p>
                                    <p class="mt-1 text-xs uppercase tracking-wide text-slate-500">Weight: {{ $item['weight'] }}%</p>
                                </div>
                                <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $item['complete'] ? 'bg-[#f3debc] text-[#7a5532]' : 'bg-[#efe2cf] text-[#8a6746]' }}">
                                    {{ $item['complete'] ? 'Done' : 'Pending' }}
                                </span>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
