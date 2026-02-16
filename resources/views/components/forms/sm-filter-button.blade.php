@props([
    'selected' => false, // is button active
    'secondary' => false,
])

<div class="inline-block">
    <div class="relative group/sm-filter-button">
        @if($attributes->has('href'))
            <a {{ $attributes->merge([
                'class' => "relative inline-block p-px text-sm font-semibold leading-4 text-textcol " . 
                           ($secondary ? "bg-accent" : "bg-dark") . 
                           " shadow-lg cursor-pointer rounded-lg shadow-zinc-900 transition-transform duration-300 ease-in-out hover:scale-105 active:scale-95 " . 
                           ($selected ? 'ring-2 ring-primary' : '')
            ]) }}>
        @else
            <button {{ $attributes->merge([
                'type' => 'button',
                'class' => "relative inline-block p-px text-sm font-semibold leading-4 text-textcol " . 
                           ($secondary ? "bg-accent" : "bg-dark") . 
                           " shadow-lg cursor-pointer rounded-lg shadow-zinc-900 transition-transform duration-300 ease-in-out hover:scale-105 active:scale-95 " .
                           ($selected ? 'ring-2 ring-primary' : '')
            ]) }}>
        @endif

            <span
                class="absolute inset-0 rounded-lg bg-gradient-to-r from-primary via-primary to-secondary p-[2px] opacity-0 transition-opacity duration-350 group-hover/sm-filter-button:opacity-100"></span>

            <span class="relative z-10 block px-3 py-1 rounded-lg {{ $secondary ? 'bg-accent' : 'bg-darker' }}">
                <div class="relative z-10 flex items-center justify-center">
                    <span class="transition-all duration-350">{{ $slot }}</span>
                </div>
            </span>

        @if($attributes->has('href'))
            </a>
        @else
            </button>
        @endif
    </div>
</div>
