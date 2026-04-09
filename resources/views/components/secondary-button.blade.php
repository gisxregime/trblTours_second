<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center rounded-md border border-[#d4a563]/70 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-[#7a5532] shadow-sm transition duration-150 ease-in-out hover:bg-[#fff7ec] focus:outline-none focus:ring-2 focus:ring-[#d4a563]/45 focus:ring-offset-2 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
