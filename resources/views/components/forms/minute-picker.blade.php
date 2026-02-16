@props([
    'value' => 0,
    'min' => 0,
    'max' => 1440,
    'step' => 1,
    'name' => null,
])

<div
    x-data="{
        minutes: {{ (int) $value }},
        min: {{ (int) $min }},
        max: {{ (int) $max }},
        step: {{ (int) $step }},
        inc() { this.minutes = Math.min(this.minutes + this.step, this.max) },
        dec() { this.minutes = Math.max(this.minutes - this.step, this.min) }
    }"
    class="flex items-center gap-2"
>
    {{-- minus button --}}
    <x-forms.sm-button type="button" @click="dec">
        −
    </x-forms.sm-button>

    {{-- input --}}
    <div class="flex h-[44px] text-[14px] text-textcol/60 w-[90px]">
        <input
            type="text"
            readonly
            x-model="minutes"
            @if($name) name="{{ $name }}" @endif
            class="input w-full text-center text-textcol bg-slate-950/50 px-3 py-1
                   rounded-lg border border-textcol/10
                   focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2
                   focus:ring-offset-darker transition-all duration-350 ease-in-out"
        >
    </div>

    {{-- plus button --}}
    <x-forms.sm-button type="button" @click="inc">
        +
    </x-forms.sm-button>
</div>
