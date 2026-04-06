<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-slate-900">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="bg-slate-100 py-8">
        <div class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 sm:px-6 lg:px-8">
            <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Total Users</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">1,284</p>
                </article>
                <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Active Guides</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">42</p>
                </article>
                <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Open Tickets</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">7</p>
                </article>
                <article class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Monthly Revenue</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">$84,300</p>
                </article>
            </section>

            <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Recent Platform Activity</h3>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="text-slate-500">
                            <tr>
                                <th class="py-2 pe-4">Event</th>
                                <th class="py-2 pe-4">User</th>
                                <th class="py-2 pe-4">Time</th>
                                <th class="py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700">
                            <tr class="border-t border-slate-100">
                                <td class="py-3 pe-4">Guide profile approved</td>
                                <td class="py-3 pe-4">Aisha K.</td>
                                <td class="py-3 pe-4">10 mins ago</td>
                                <td class="py-3"><span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-medium text-emerald-700">Completed</span></td>
                            </tr>
                            <tr class="border-t border-slate-100">
                                <td class="py-3 pe-4">Refund request submitted</td>
                                <td class="py-3 pe-4">Jonas P.</td>
                                <td class="py-3 pe-4">28 mins ago</td>
                                <td class="py-3"><span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-medium text-amber-700">Reviewing</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
