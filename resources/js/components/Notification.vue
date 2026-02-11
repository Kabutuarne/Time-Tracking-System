<script setup>
import { ref, computed, onMounted } from 'vue'

const props = defineProps({
  flashData: {
    type: Object,
    default: () => ({})
  }
})

const visible = ref(false)
const message = ref('')
const type = ref('success')
const isLeaving = ref(false)

const titleMap = {
  success: 'Success',
  error: 'Error',
  warning: 'Warning',
  info: 'Notice',
}

const stylesMap = {
  success: {
    wrapper: 'bg-slate-950/40 ring-primary/30',
    glow: 'bg-primary/20',
    iconBg: 'bg-primary/10 text-primary',
    icon: 'fa-solid fa-check',
    progressBar: 'bg-primary'
  },
  error: {
    wrapper: 'bg-slate-950/40 ring-red-400/30',
    glow: 'bg-red-400/20',
    iconBg: 'bg-red-400/10 text-red-400',
    icon: 'fa-solid fa-triangle-exclamation',
    progressBar: 'bg-red-400'
  },
  warning: {
    wrapper: 'bg-slate-950/40 ring-yellow-400/30',
    glow: 'bg-yellow-400/20',
    iconBg: 'bg-yellow-400/10 text-yellow-400',
    icon: 'fa-solid fa-exclamation',
    progressBar: 'bg-yellow-400'
  },
  info: {
    wrapper: 'bg-slate-950/40 ring-secondary/30',
    glow: 'bg-secondary/20',
    iconBg: 'bg-secondary/10 text-secondary',
    icon: 'fa-solid fa-circle-info',
    progressBar: 'bg-secondary'
  },
}

const title = computed(() => titleMap[type.value])
const styles = computed(() => stylesMap[type.value])

function close() {
  isLeaving.value = true
  setTimeout(() => {
    visible.value = false
    isLeaving.value = false
  }, 300) // Match animation duration
}

function show(payload) {
  type.value = payload.type
  message.value = payload.message
  visible.value = true
  setTimeout(close, payload.timeout ?? 4500)
}

onMounted(() => {
  if (Object.keys(props.flashData).length > 0) {
    const types = ['success', 'error', 'warning', 'info']
    
    for (const t of types) {
      if (props.flashData[t]) {
        show({ type: t, message: props.flashData[t] })
        break
      }
    }
  }
})
</script>

<template>
  <Transition
    enter-active-class="transition-all duration-300 ease-out"
    enter-from-class="translate-x-full opacity-0"
    enter-to-class="translate-x-0 opacity-100"
    leave-active-class="transition-all duration-300 ease-in"
    leave-from-class="translate-x-0 opacity-100"
    leave-to-class="translate-x-full opacity-0"
  >
    <div 
      v-if="visible" 
      class="fixed top-6 right-6 z-50 pointer-events-auto"
    >
      <div 
        class="relative overflow-hidden rounded-xl backdrop-blur-xl ring-1 transition-all duration-300 w-96"
        :class="styles.wrapper"
      >
        <!-- Glow effect -->
        <div 
          class="absolute -right-4 -top-4 h-24 w-24 rounded-full blur-2xl transition-all duration-500"
          :class="styles.glow"
        ></div>
        
        <!-- Content -->
        <div class="relative p-4">
          <div class="flex items-start gap-3">
            <!-- Icon -->
            <div class="flex-shrink-0">
              <div 
                class="flex h-10 w-10 items-center justify-center rounded-lg transition-all duration-300"
                :class="styles.iconBg"
              >
                <i :class="styles.icon" class="text-sm"></i>
              </div>
            </div>
            
            <!-- Text content -->
            <div class="flex-1 min-w-0 pt-0.5">
              <div class="flex items-start justify-between gap-2 mb-1">
                <h3 class="text-sm font-semibold text-textcol">{{ title }}</h3>
                <button 
                  @click="close" 
                  class="flex-shrink-0 text-textcol2 hover:text-textcol transition-colors duration-200 -mt-0.5"
                >
                  <i class="fa-solid fa-times text-xs"></i>
                </button>
              </div>
              <p class="text-sm text-textcol2 leading-relaxed">{{ message }}</p>
            </div>
          </div>
        </div>

        <!-- Progress bar -->
        <div class="h-1 bg-white/5">
          <div 
            class="h-full origin-left animate-shrink"
            :class="styles.progressBar"
            style="animation-duration: 4.5s"
          ></div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<style scoped>
@keyframes shrink {
  from {
    transform: scaleX(1);
  }
  to {
    transform: scaleX(0);
  }
}

.animate-shrink {
  animation: shrink linear forwards;
}
</style>