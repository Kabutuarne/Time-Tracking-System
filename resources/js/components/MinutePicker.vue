<template>
    <div class="flex items-center gap-2">
        <!-- Minus -->
        <button
            type="button"
            @click="dec"
            class="px-3 py-2 rounded-lg border border-textcol/10
                   bg-slate-950/50 text-textcol
                   hover:bg-slate-900 transition-all duration-200"
        >
            −
        </button>

        <!-- Input -->
        <div class="flex h-[44px] text-[14px] text-textcol/60 w-[90px]">
            <input
                type="text"
                :value="minutes"
                :name="name"
                readonly
                class="input w-full text-center text-textcol bg-slate-950/50 px-3 py-1
                       rounded-lg border border-textcol/10
                       focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2
                       focus:ring-offset-darker transition-all duration-350 ease-in-out"
            />
        </div>

       <!-- plus -->
        <button
            type="button"
            @click="inc"
            class="px-3 py-2 rounded-lg border border-textcol/10
                   bg-slate-950/50 text-textcol
                   hover:bg-slate-900 transition-all duration-200"
        >
            +
        </button>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
    modelValue: {
        type: Number,
        default: 0,
    },
    min: {
        type: Number,
        default: 0,
    },
    max: {
        type: Number,
        default: 1440,
    },
    step: {
        type: Number,
        default: 1,
    },
    name: {
        type: String,
        default: null,
    },
})

const emit = defineEmits(['update:modelValue'])

const minutes = ref(props.modelValue)

// to keep internal state in synch
watch(
    () => props.modelValue,
    (newVal) => {
        minutes.value = newVal
    }
)

function inc() {
    const next = Math.min(minutes.value + props.step, props.max)
    minutes.value = next
    emit('update:modelValue', next)
}

function dec() {
    const next = Math.max(minutes.value - props.step, props.min)
    minutes.value = next
    emit('update:modelValue', next)
}
</script>
