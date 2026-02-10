<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { emitter } from '../eventBus'

const visible = ref(false)
const message = ref('')
const type = ref('success')

const titleMap = {
    success: 'Success',
    error: 'Error',
    warning: 'Warning',
    info: 'Notice',
}

const stylesMap = {
    success: {
        wrapper: 'bg-slate-950/50 ring-primary/30',
        glow: 'bg-primary/30',
        iconBg: 'bg-primary/20 text-primary',
        icon: 'fa-solid fa-check',
    },
    error: {
        wrapper: 'bg-slate-950/50 ring-red-400/30',
        glow: 'bg-red-400/30',
        iconBg: 'bg-red-400/20 text-red-400',
        icon: 'fa-solid fa-triangle-exclamation',
    },
    warning: {
        wrapper: 'bg-slate-950/50 ring-yellow-400/30',
        glow: 'bg-yellow-400/30',
        iconBg: 'bg-yellow-400/20 text-yellow-400',
        icon: 'fa-solid fa-exclamation',
    },
    info: {
        wrapper: 'bg-slate-950/50 ring-secondary/30',
        glow: 'bg-secondary/30',
        iconBg: 'bg-secondary/20 text-secondary',
        icon: 'fa-solid fa-circle-info',
    },
}

const title = computed(() => titleMap[type.value])
const styles = computed(() => stylesMap[type.value])

function close() {
    visible.value = false
}

function showNotification(payload) {
    type.value = payload.type ?? 'success'
    message.value = payload.message ?? ''
    visible.value = true
    setTimeout(close, payload.timeout ?? 4500)
}

onMounted(() => {
    emitter.on('notify', showNotification)
})

onBeforeUnmount(() => {
    emitter.off('notify', showNotification)
})
</script>
