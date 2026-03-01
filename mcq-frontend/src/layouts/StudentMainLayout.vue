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
        <span class="text-xl font-bold text-indigo-600 italic">Intellecta</span>
      </div>

      <!-- Menu -->
      <nav class="flex-1 px-3 space-y-0.5">
        <!-- exams -->       
        <router-link
          to="/student/dashboard"
          class="nav-link"
          active-class="active-link"
        >
          <div class="icon-container"><FileText :size="18" /></div>
          <span class="text-sm font-medium tracking-tight">Enrolled Exams</span>
        </router-link>
        
        <!-- Exam history -->
        <router-link
          to="/student/exam-history"
          class="flex items-center gap-3 px-4 py-1 rounded-xl text-black hover:bg-slate-50 transition w-full"
          active-class="active-link"
        >
          <div class="icon-container"><History :size="18" /></div>
          <span class="text-sm font-medium tracking-tight">Exam History</span>
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
import Header from '@/components/student/StudentHeader.vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { LayoutDashboard, FileText, History } from 'lucide-vue-next';

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