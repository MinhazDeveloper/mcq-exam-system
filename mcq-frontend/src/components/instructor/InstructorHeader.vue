<template>
  <header class="h-15 bg-white border-b border-slate-100 flex items-center justify-between px-10 fixed top-0 right-0 left-64 z-10">
    <div class="flex items-center gap-6">
      <h1 class="text-2xl font-extrabold text-slate-800 mr-4">{{ title }}</h1>
    </div>

    

    <div class="flex items-center gap-3 pl-6 border-l border-slate-200">
      <div class="text-right">
        <p class="text-sm font-bold text-slate-700 capitalize">
          {{ authStore.user?.name || 'Loading...' }}
        </p>
        <p class="text-xs text-slate-400">Instructor</p>
      </div>
      
      <img 
        :src="userAvatar" 
        :alt="authStore.user?.name"
        class="w-10 h-10 rounded-full border-2 border-indigo-50 shadow-sm object-cover"
      >
    </div>
    
  </header>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const route = useRoute()
const authStore = useAuthStore()

const pageTitle = computed(() => {
  return route.meta.title || 'Dashboard'
})

const userAvatar = computed(() => {
  const name = authStore.user?.name || 'User'
  return authStore.user?.profile_photo_url || `https://ui-avatars.com/api/?name=${name.replace(' ', '+')}&background=6366f1&color=fff`
})


</script>
