<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-sky-900">
            {{ __('Tourist Dashboard') }}
        </h2>
    </x-slot>

    <div class="bg-gradient-to-b from-sky-50 via-white to-emerald-50 py-10">
        <div class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 sm:px-6 lg:px-8">
            <section class="overflow-hidden rounded-2xl border border-sky-100 bg-white shadow-sm">
                <div class="grid gap-6 p-6 md:grid-cols-2 md:p-8">
                    <div class="space-y-3">
                        <p class="text-sm font-medium uppercase tracking-wide text-sky-600">Plan your next adventure</p>
                        <h3 class="text-2xl font-semibold text-slate-900">Welcome back, {{ Auth::user()->name }}.</h3>
                        <p class="text-sm text-slate-600">Discover curated tour packages and keep all your upcoming trips in one place.</p>
                        <button class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-500">Browse New Tours</button>
                    </div>
                    <div class="rounded-xl bg-sky-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-sky-700">Next trip countdown</p>
                        <p class="mt-2 text-4xl font-bold text-slate-900">12 days</p>
                        <p class="mt-2 text-sm text-slate-600">to Coastal Heritage Loop</p>
                    </div>
                </div>
            </section>

            <section class="grid gap-4 md:grid-cols-3">
                <article class="rounded-xl border border-emerald-100 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-emerald-600">Upcoming Trips</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">3</p>
                </article>
                <article class="rounded-xl border border-amber-100 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-amber-600">Saved Tours</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">14</p>
                </article>
                <article class="rounded-xl border border-violet-100 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-violet-600">Loyalty Points</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">4,860</p>
                </article>
            </section>
        </div>
    </div>
</x-app-layout>
