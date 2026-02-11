<template>
  <transition name="fade">
    <div v-if="visible" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
      <div class="bg-darker rounded-2xl shadow-2xl ring-1 ring-white/5 max-w-md w-full p-6 space-y-4">
        <h2 class="text-xl font-bold text-textcol">{{ title }}</h2>
        <p class="text-textcol2">{{ message }}</p>

        <div class="flex justify-end gap-3 mt-4">
          <!-- Cancel Button -->
          <div class="relative group/buttonbig">
            <button type="button" 
                    class="relative inline-block p-px font-semibold leading-6 text-textcol bg-dark shadow-2xl cursor-pointer rounded-xl shadow-zinc-900 transition-transform duration-300 ease-in-out group-hover/buttonbig:scale-105 active:scale-95"
                    @click="$emit('update:visible', false)">
              <span class="absolute inset-0 rounded-xl bg-gradient-to-r from-primary via-primary to-secondary p-[2px] opacity-0 transition-opacity duration-350 group-hover/buttonbig:opacity-100"></span>
              <span class="relative z-10 block px-6 py-3 rounded-xl bg-darker">
                <div class="relative z-10 flex items-center space-x-2">
                    
                  <span class="transition-all duration-350 group-hover/buttonbig:translate-x-1"><i class="fas fa-cancel"></i> Cancel</span>
                </div>
              </span>
            </button>
          </div>

          <!-- Confirm Button -->
          <div class="relative group/buttonbig">
            <button type="button" 
                    class="relative inline-block p-px font-semibold leading-6 text-textcol bg-accent shadow-2xl cursor-pointer rounded-xl shadow-zinc-900 transition-transform duration-300 ease-in-out group-hover/buttonbig:scale-105 active:scale-95"
                    @click="confirmAction">
              <span class="absolute inset-0 rounded-xl bg-gradient-to-r from-primary via-primary to-secondary p-[2px] opacity-0 transition-opacity duration-350 group-hover/buttonbig:opacity-100"></span>
              <span class="relative z-10 block px-6 py-3 rounded-xl bg-accent">
                <div class="relative z-10 flex items-center space-x-2">
                  <span class="transition-all duration-350 group-hover/buttonbig:translate-x-1"><i class="fas fa-check"></i> Confirm</span>
                </div>
              </span>
            </button>
          </div>
        </div>

      </div>
    </div>
  </transition>
</template>

<script>
export default {
  props: {
    visible: { type: Boolean, required: true },
    title: { type: String, default: "Are you sure?" },
    message: { type: String, default: "This action cannot be undone." },
  },
  emits: ['update:visible', 'confirmed'],
  methods: {
    confirmAction() {
      this.$emit('confirmed');
      this.$emit('update:visible', false);
    }
  }
}
</script>
