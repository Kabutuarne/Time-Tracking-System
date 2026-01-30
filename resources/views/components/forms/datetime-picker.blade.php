@props([
    'name' => null,
    'value' => null
])

<div
    x-data="{
        raw: '{{ $value }}',
        display: '',

        open() {
            this.$refs.hidden.showPicker()
        },

        format(val) {
            if (!val) return ''

            const d = new Date(val)
            if (isNaN(d)) return ''

            return d.toLocaleString(undefined, {
                year: 'numeric',
                month: 'short',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            })
        },

        sync(e) {
            this.raw = e.target.value
            this.display = this.format(this.raw)
        },

        init() {
            this.display = this.format(this.raw)
        }
    }"
    class="relative flex items-center w-[230px]"
>
    {{-- visible readonly input --}}
    <input
        type="text"
        readonly
        x-model="display"
        class="w-full rounded-xl bg-slate-950/50 border border-white/10 px-4 py-3 pr-12
               text-textcol cursor-default
               focus:outline-none focus:ring-2 focus:ring-primary
               transition"
    >

    {{-- hidden native datetime input --}}
    <input
        x-ref="hidden"
        type="datetime-local"
        @if($name) name="{{ $name }}" @endif
        :value="raw"
        @change="sync"
        class="absolute inset-0 opacity-0 pointer-events-none"
    >

    {{-- calendar button --}}
    <button
        type="button"
        @click="open"
        class="absolute right-3 flex items-center justify-center
               text-textcol/70 hover:text-textcol transition"
    >
        <svg
            class="w-5 h-5"
            fill="currentColor"
            viewBox="0 0 24 24"
            aria-hidden="true"
        >
            <path
                d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h1V3a1 1 0 0 1 1-1Zm13 9H4v8a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-8Z"
            />
        </svg>
    </button>
</div>
