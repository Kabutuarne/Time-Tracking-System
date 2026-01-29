<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'

const el = document.getElementById('user-search')
if (!el) throw new Error('UserSearch mount element not found')

// Start empty: only track newly added users
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
           class="flex items-center justify-between bg-slate-950/50 px-3 py-2 rounded-lg text-textcol">
        <span>{{ user.username }}</span>
        <button type="button"
                @click="selectedUsers = selectedUsers.filter(u => u.id !== user.id)"
                class="text-red-400 text-xs">Remove</button>
        <input type="hidden" name="users[]" :value="user.id">
      </div>
    </div>
  </div>
</template>
