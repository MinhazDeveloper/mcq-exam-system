<template>
  <div class="flex h-screen bg-[#F8FAFC] overflow-hidden">
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col fixed inset-y-0 left-0 z-20">
    <!-- Logo -->
    <div class="p-8 flex items-center gap-3">
      <div class="text-indigo-600">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
        </svg>
      </div>
      <span class="text-xl font-bold text-indigo-600 italic">ExamAdmin</span>
    </div>

    <!-- Menu -->
    <nav class="flex-1 px-3 space-y-0.5">
      <!-- dashboard -->
      <router-link
        to="/admin/dashboard"
        class="flex items-center gap-3 px-4 py-1 rounded-xl text-black hover:bg-slate-50 transition w-full"
        active-class="active-link"
      >
        <!-- Icon box -->
        <span class="p-2 rounded-lg text-black">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <rect x="3" y="3" width="7" height="7" rx="1" />
            <rect x="14" y="3" width="7" height="7" rx="1" />
            <rect x="3" y="14" width="7" height="7" rx="1" />
            <rect x="14" y="14" width="7" height="7" rx="1" />
          </svg>
        </span>
        <!-- Text -->
        <span class="text-xs font-medium text-slate-900 tracking-tight">
          Dashboard
        </span>
      </router-link>

      <!-- users -->       
      <router-link
        to="/admin/users"
        class="flex items-center gap-3 px-4 py-1 rounded-xl text-black hover:bg-slate-50 transition w-full"
        active-class="active-link"
      >
        <!-- Icon box -->
        <span class="p-2 rounded-lg text-black">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <!-- User icon -->
            <circle cx="12" cy="8" r="4" />
            <path d="M4 20c0-4 4-6 8-6s8 2 8 6" />
          </svg>
        </span>
        <!-- Text -->
        <span class="text-xs font-medium text-slate-900 tracking-tight">
          Users
        </span>
      </router-link>
      
      <!-- exams -->
      <router-link
        to="/admin/exams"
        class="flex items-center gap-3 px-4 py-1 rounded-xl text-black hover:bg-slate-50 transition w-full"
        active-class="active-link"
      >
        <span class="p-2 rounded-lg text-black icon-container">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
          </svg>
        </span>
        
        <span class="text-xs font-medium text-slate-900 tracking-tight">
          Exams
        </span>
      </router-link>

      <!-- finance -->
      <router-link
        to="/admin/finance"
        class="flex items-center gap-3 px-4 py-1 rounded-xl text-black hover:bg-slate-50 transition w-full"
        active-class="active-link"
      >
        <span class="p-2 rounded-lg text-black icon-container">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </span>
        <span class="text-xs font-medium text-slate-900 tracking-tight">Finance</span>
      </router-link>

      <!-- settings -->
      <router-link
        to="/admin/settings"
        class="flex items-center gap-3 px-4 py-1 rounded-xl text-black hover:bg-slate-50 transition w-full"
        active-class="active-link"
      >
        <span class="p-2 rounded-lg text-black icon-container">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        </span>
        <span class="text-xs font-medium text-slate-900 tracking-tight">Settings</span>
      </router-link>

      <!-- security -->
      <router-link
        to="/admin/security"
        class="flex items-center gap-3 px-4 py-1 rounded-xl text-black hover:bg-slate-50 transition w-full"
        active-class="active-link"
      >
        <span class="p-2 rounded-lg text-black icon-container">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
        </span>
        <span class="text-xs font-medium text-slate-900 tracking-tight">Security</span>
      </router-link>
    </nav>

    <div class="mt-auto bg-white border-t border-slate-100 p-4">
      <!-- <button @click="handleLogout" class="flex items-center gap-3 w-full text-left px-4 py-3 rounded-xl text-rose-600 hover:bg-rose-50 transition">
        <span class="p-2 bg-rose-50 rounded-lg text-rose-600">⤴️</span>
        <span class="text-xs font-medium">Logout</span>
      </button> -->

      <button 
        @click="handleLogout" 
        class="flex items-center gap-3 px-4 py-2 w-full group transition-all duration-200"
      >
        <span class="text-xs transition-transform group-hover:-translate-x-1">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
        </span>
        
        <span class="text-sm font-bold text-rose-500 tracking-wide">
          Logout
        </span>
      </button>
    </div>
  </aside>
    

    <div class="flex-1 flex flex-col ml-64"> 
      <Header />

      <main class="flex-1 pt-20 overflow-y-auto bg-slate-50 p-10">
        <router-view>
          
        </router-view> 
      </main>
      
    </div>    
  </div>
</template>

<script setup>
import Header from '@/components/Header.vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const auth = useAuthStore()

const handleLogout = async () => {
  await auth.logout()
  router.replace('/login')
}
</script>

<style scoped>
@reference "tailwindcss";
/* Sidebar logo */
.logo { @apply p-6 font-semibold text-indigo-600 text-xl italic flex items-center gap-3; }

/* Nav link */
.nav-link { 
  @apply flex items-center gap-3 px-4 py-1 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition w-full;
}

/* Icon box inside nav link */
.icon-container { 
  /* @apply p-2 rounded-lg w-8 h-8 flex items-center justify-center text-slate-400;  */
  @apply flex items-center justify-center w-8 h-8 rounded-lg text-slate-500 transition-colors;
}

/* Active link styling (applied via router active-class)
   - Slight indigo background for the whole pill
   - Dark indigo icon background with white icon */
.active-link { @apply bg-indigo-50 text-indigo-600 font-semibold; }
.active-link .icon-container { @apply bg-indigo-600 text-white; }

/* Logout button - emphasize danger color */
.logout-btn { @apply flex items-center gap-3 w-full text-left px-4 py-3 rounded-xl text-rose-600 hover:bg-rose-50 transition; }
.logout-btn .icon { @apply p-2 bg-rose-50 rounded-lg text-rose-600; }
</style>
