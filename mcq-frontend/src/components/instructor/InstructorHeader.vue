<template>
  <header class="h-15 bg-white border-b border-slate-100 flex items-center justify-between px-10 fixed top-0 right-0 left-64 z-10">
    <div class="flex items-center gap-6">
      <h1 class="text-2xl font-extrabold text-slate-800 mr-4">{{ title }}</h1>
      <!-- <div class="relative w-96">
        <span class="absolute inset-y-0 left-4 flex items-center text-slate-400">🔍</span>
        <input type="text" placeholder="Search..." class="w-full bg-slate-100 border-none rounded-full py-2.5 pl-11 focus:ring-2 focus:ring-indigo-300 transition">
      </div> -->
    </div>

    <div class="flex items-center gap-6">
      <div class="relative text-slate-400 hover:text-indigo-600 cursor-pointer">
        <span class="text-xl">🔔</span>
        <span class="absolute -top-1 -right-1 bg-red-500 w-2 h-2 rounded-full"></span>
      </div>
      <div class="flex items-center gap-3 pl-6 border-l border-slate-200">
        <div class="text-right">
          <!-- <p class="text-sm font-bold">Thomas Anderson</p> -->
          <p class="text-xs text-slate-400">Instructor</p>
        </div>
        <img src="https://ui-avatars.com/api/?name=Thomas+Anderson&background=random" class="w-10 h-10 rounded-full border-2 border-white shadow-sm">
      </div>
    </div>
  </header>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

</script>
