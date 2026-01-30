@props([
    'name' => null,
    'checked' => false,
    'value' => 1,
    'secondary' => false,
])

<label
    x-data="{ on: {{ $checked ? 'true' : 'false' }} }"
    class="inline-flex items-center gap-3 cursor-pointer select-none"
>
    {{-- hidden checkbox --}}
    <input
        type="checkbox"
        class="sr-only"
        x-model="on"
        @if($name) name="{{ $name }}" @endif
        value="{{ $value }}"
    >

    {{-- checkbox shell --}}
    <div
        class="relative w-10 h-10 rounded-xl p-px
               transition-transform duration-300 ease-in-out
               hover:scale-105 active:scale-95
               {{ $secondary ? 'bg-accent' : 'bg-dark' }}
               shadow-2xl shadow-zinc-900"
    >
        {{-- hover gradient --}}
        <span
            class="absolute inset-0 rounded-xl bg-gradient-to-r
                   from-primary via-primary to-secondary
                   opacity-0 transition-opacity duration-300
                   group-hover:opacity-100"
        ></span>

        {{-- inner --}}
        <div
            class="relative z-10 flex items-center justify-center w-full h-full
                   rounded-xl
                   {{ $secondary ? 'bg-accent' : 'bg-darker' }}
                   border border-white/10
                   transition-colors duration-300"
            :class="on ? 'text-primary' : 'text-textcol/40'"
        >
        <svg
            class="w-6 h-6 transition-all duration-200"
            fill="currentColor"
            viewBox="0 0 20 20"
            aria-hidden="true"
            :class="on
                ? 'opacity-100 scale-100'
                : 'opacity-0 scale-1'"
        >
            <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M16.704 5.29a1 1 0 0 1 0 1.415l-7.25 7.25a1 1 0 0 1-1.415 0l-3.25-3.25a1 1 0 1 1 1.415-1.415l2.543 2.543 6.543-6.543a1 1 0 0 1 1.414 0Z"
            />
        </svg>

        </div>
    </div>

    {{-- text --}}
    @if(trim($slot))
        <span class="font-semibold text-textcol">
            {{ $slot }}
        </span>
    @endif
</label>
