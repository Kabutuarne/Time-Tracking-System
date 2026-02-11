@props([
    'name' => 'option',
    'options' => [],
    'selected' => null,
    'width' => 'w-40',
])

@php
    $selectedValue = $selected ?? array_key_first($options);
    $selectedLabel = $options[$selectedValue] ?? '';
@endphp

<div
    x-data="{ label: '{{ ucfirst($selectedLabel) }}' }"
    class="relative text-textcol2 inline-block group/dropdown {{ $width }}"
>
    {{-- Selected --}}
    <div
        class="flex items-center justify-between rounded-xl cursor-pointer px-3 py-2 text-sm
               bg-slate-950/60 ring-1 ring-white/5
               hover:bg-slate-950/80 transition">
        <span class="text-textcol" x-text="label"></span>

        <svg
            class="w-4 h-4 text-textcol2 fill-current transform -rotate-90 group-hover/dropdown:rotate-0 transition duration-300"
            viewBox="0 0 512 512">
            <path d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/>
        </svg>
    </div>

    {{-- Options --}}
    <div
        class="absolute left-0 top-full w-full rounded-xl p-1 z-50
               bg-slate-950/80 backdrop-blur
               ring-1 ring-white/5
               opacity-0 -translate-y-1 pointer-events-none
               group-hover/dropdown:opacity-100 group-hover/dropdown:translate-y-0 group-hover/dropdown:pointer-events-auto
               transition-all duration-200">

        @foreach($options as $value => $label)
            <label class="block">
                <input
                    type="radio"
                    name="{{ $name }}"
                    value="{{ $value }}"
                    class="hidden peer"
                    @checked($value === $selectedValue)
                    @change="label = '{{ ucfirst($label) }}'"
                    {{ $attributes }}
                >

                <span
                    class="block px-3 py-2 rounded-lg text-sm cursor-pointer
                           text-textcol2
                           hover:bg-primary/10 hover:text-textcol
                           peer-checked:hidden transition">
                    {{ ucfirst($label) }}
                </span>
            </label>
        @endforeach
    </div>
</div>