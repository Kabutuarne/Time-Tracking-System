<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'

const el = document.getElementById('user-search')
if (!el) throw new Error('UserSearch mount element not found')

//only track newly added users
const selectedUsers = ref([])

const query = ref('')
const results = ref([])
const loading = ref(false)
const showResults = ref(false)

watch(query, async (q) => {
  if (q.length < 2) {
    results.value = []
    return
  }

  loading.value = true
  const { data } = await axios.get(`/users/search`, { params: { q } })
  // exclude already selected users
  results.value = data.filter(u => !selectedUsers.value.some(su => su.id === u.id))
  loading.value = false
})

function toggleUser(user) {
  if (!selectedUsers.value.some(u => u.id === user.id)) {
    selectedUsers.value.push(user)
  }
}
</script>

<template>
  <div class="relative space-y-2">
    <input
      v-model="query"
      @focus="showResults = true"
      placeholder="Search by username or email..."
      class="w-full px-3 py-2.5 rounded-lg bg-slate-950/50 text-textcol border border-textcol/10 focus:outline-none focus:ring-2 focus:ring-primary"
    />

    <div v-if="showResults && query.length >= 2" 
         @click.away="showResults=false"
         class="absolute top-full left-0 right-0 mt-2 max-h-64 overflow-y-auto bg-slate-950/95 rounded-lg border border-textcol/10 z-50">
      
      <div v-if="loading" class="p-3 text-xs text-textcol2">Searching...</div>
      <div v-else-if="results.length === 0" class="p-3 text-xs text-textcol2">No users found</div>

      <button
        v-for="user in results"
        :key="user.id"
        @click.prevent="toggleUser(user)"
        class="w-full flex items-center gap-3 px-3 py-2 hover:bg-primary/10 text-left"
      >
        <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center">
          <span class="text-xs font-semibold text-primary">
            {{ user.username.substring(0,2).toUpperCase() }}
          </span>
        </div>
        <div class="flex-1">
          <p class="text-sm text-textcol font-medium">{{ user.username }}</p>
          <p class="text-xs text-textcol2">{{ user.email }}</p>
        </div>
      </button>
    </div>

    <!-- the new selected users -->
    <div class="space-y-2 mt-2">
      <div v-if="selectedUsers.length === 0" class="text-xs text-textcol2">No users selected</div>

      <div v-for="user in selectedUsers" :key="user.id"
           class="flex items-center justify-between bg-slate-950/50 h-[40px] px-3 rounded-lg text-textcol">
        <span>{{ user.username }}</span>
        <button type="button"
                @click="selectedUsers = selectedUsers.filter(u => u.id !== user.id)"
                class="relatives text-xs scale-50% p-0 m-0 border-none bg-transparent cursor-pointer text-base transition-transform duration-200 ease-in-out group/trash scale-[0.45]">
                <svg class="w-16 h-16 transition-transform duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)] drop-shadow-md overflow-visible group-hover/trash:scale-[1.08] group-hover/trash:rotate-[3deg] group-active:scale-[0.96] group-active:rotate-[-1deg]"
                    viewBox="0 -10 64 74" xmlns="http://www.w3.org/2000/svg">
                    <g id="trash-can">
                        <rect x="16" y="24" width="32" height="30" rx="3" ry="3" fill="#01BAEF"></rect>
                        <g transform-origin="12 18" id="lid-group"
                            class="transition-transform duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)] group-hover/trash:rotate-[-28deg] group-hover/trash:translate-y-[2px] group-active/trash:rotate-[-12deg] group-active:scale-[0.98]">
                            <rect x="12" y="12" width="40" height="6" rx="2" ry="2" fill="#01BAEF"></rect>
                            <rect x="26" y="8" width="12" height="4" rx="2" ry="2" fill="#01BAEF"></rect>
                        </g>
                    </g>
                </svg>    
        </button>
        <input type="hidden" name="users[]" :value="user.id">
      </div>
    </div>
  </div>
</template>
