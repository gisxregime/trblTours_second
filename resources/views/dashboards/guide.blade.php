<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-emerald-900">
            {{ __('Guide Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto grid w-full max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[280px_1fr] lg:px-8">
            <aside class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Guide profile</p>
                <h3 class="mt-2 text-lg font-semibold text-slate-900">{{ Auth::user()->name }}</h3>
                <p class="mt-1 text-sm text-slate-600">Lead guide for cultural and eco tours.</p>
                <ul class="mt-4 space-y-2 text-sm text-slate-700">
                    <li>Rating: 4.9/5</li>
                    <li>Groups this month: 9</li>
                    <li>On-time starts: 100%</li>
                </ul>
            </aside>

            <section class="space-y-5">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Today's Schedule</h3>
                    <div class="mt-4 space-y-3">
                        <div class="rounded-xl border border-slate-200 p-4">
                            <p class="text-sm font-medium text-slate-900">08:30 - City Market Walk</p>
                            <p class="text-sm text-slate-600">12 guests, pickup at East Gate.</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 p-4">
                            <p class="text-sm font-medium text-slate-900">14:00 - Riverside Heritage Tour</p>
                            <p class="text-sm text-slate-600">8 guests, language: English/French.</p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-blue-100 bg-blue-50 p-4">
                        <p class="text-xs uppercase tracking-wide text-blue-700">Pending Reviews</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">5</p>
                    </div>
                    <div class="rounded-xl border border-rose-100 bg-rose-50 p-4">
                        <p class="text-xs uppercase tracking-wide text-rose-700">Messages</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">11</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
