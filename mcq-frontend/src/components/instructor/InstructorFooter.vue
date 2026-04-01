<template>
  <footer class="mt-auto py-2 px-8 border-t border-slate-100 bg-white/80 backdrop-blur-sm flex flex-col md:flex-row items-center justify-between text-black text-[11px]">
    
    <div class="hidden md:block w-32"></div>

    <div class="mb-1 md:mb-0 text-center flex-1 tracking-tight">
      &copy; {{ currentYear }} 
      <span class="font-bold text-black ml-0.5 uppercase">Intellecta Digital</span>. 
      All rights reserved.
    </div>

    <div class="flex items-center gap-3 w-full md:w-32 justify-center md:justify-end font-medium">
      <div class="flex items-center gap-1">
        <!-- <span class="relative flex h-1.5 w-1.5">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-green-500"></span>
        </span> -->
        <!-- <span class="text-slate-500 uppercase text-[9px]">v1.0.2</span> -->
      </div>
    </div>
  </footer>
</template>

<script setup>
import { computed } from 'vue';
const currentYear = computed(() => new Date().getFullYear());
</script>