@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-md border-[#d4a563]/55 shadow-sm focus:border-[#d4a563] focus:ring-[#d4a563]/35']) }}>
