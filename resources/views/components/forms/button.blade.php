@props([
'secondary' => false,
])

<div class="flex items-center">
    <div class="relative group/buttonbig">
        {{-- check if its an anchor type or a button type --}}
        @if($attributes->has('href'))
            <a {{ $attributes->merge(['class' => "relative inline-block p-px font-semibold leading-6 text-textcol " . ($secondary ? "bg-accent" : " bg-dark") . " shadow-2xl cursor-pointer rounded-xl shadow-zinc-900 transition-transform duration-300 ease-in-out group-hover/buttonbig:scale-105 active:scale-95"]) }}>
        @else
                <button {{ $attributes->merge(['type' => 'submit', 'class' => "relative inline-block p-px font-semibold leading-6 text-textcol " . ($secondary ? "bg-accent" : "bg-dark") . " shadow-2xl cursor-pointer rounded-xl shadow-zinc-900 transition-transform duration-300 ease-in-out group-hover/buttonbig:scale-105 active:scale-95"]) }}>
            @endif
                <span
                    class="absolute inset-0 rounded-xl bg-gradient-to-r from-primary via-primary to-secondary p-[2px] opacity-0 transition-opacity duration-350 group-hover/buttonbig:opacity-100"></span>

                <span class="relative z-10 block px-6 py-3 rounded-xl {{ $secondary ? 'bg-accent' : 'bg-darker' }}">
                    <div class="relative z-10 flex items-center space-x-2">
                        <span
                            class="transition-all duration-350 group-hover/buttonbig:translate-x-1">{{ $slot }}</span>
                        <svg class="w-6 h-6 transition-transform duration-350 group-hover/buttonbig:translate-x-1"
                            data-slot="icon" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd"
                                d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                                fill-rule="evenodd"></path>
                        </svg>
                    </div>
                </span>

                @if($attributes->has('href'))
                    </a>
                @else
            </button>
        @endif
    </div>
</div>