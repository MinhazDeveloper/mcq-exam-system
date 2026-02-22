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
      <!-- dashboard -->
      <router-link
        to="/instructor/dashboard"
        class="nav-link"
        active-class="active-link"
      >
        <div class="icon-container"><LayoutDashboard :size="16" /></div>
        <span class="text-sm font-medium tracking-tight">Dashboard</span>
      </router-link>

      <!-- exams -->    
      <router-link
        to="/instructor/exams"
        class="nav-link"
        active-class="active-link"
      >
        <div class="icon-container"><BookOpenCheck :size="16" /></div>
        <span class="text-sm font-medium tracking-tight">Exams</span>
      </router-link>
  
      <!-- Question Bank -->
      <router-link
        to="/instructor/question"
        class="nav-link"
        active-class="active-link"
      >
        <div class="icon-container"><Database :size="16" /></div>
        <span class="text-sm font-medium tracking-tight">Question Bank</span>
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
  
  <div class="p-8 bg-gray-50 min-h-screen">
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Exams</h1>
        <p class="text-gray-500">Manage your examination content and settings</p>
      </div>
      <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
        <span class="text-xl">+</span> Create New Exam
      </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="p-4 border-b border-gray-100 flex justify-between gap-4">
        <div class="relative flex-1 max-w-md">
          <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </span>
          <input 
            type="text" 
            placeholder="Search exams..." 
            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
          />
        </div>
        <button class="flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
          </svg>
          All Status
        </button>
      </div>

      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="text-xs font-semibold text-gray-400 uppercase bg-gray-50 border-b border-gray-100">
            <th class="px-6 py-4">Exam Title</th>
            <th class="px-6 py-4 text-center">Questions</th>
            <th class="px-6 py-4 text-center">Attempts</th>
            <th class="px-6 py-4 text-center">Status</th>
            <th class="px-6 py-4 text-center">Last Modified</th>
            <th class="px-6 py-4">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="exam in exams" :key="exam.id" class="hover:bg-gray-50 transition">
            <td class="px-6 py-4">
              <div class="font-bold text-gray-800">{{ exam.title }}</div>
              <div class="text-xs text-gray-400">{{ exam.category }} • {{ exam.duration }}</div>
            </td>
            <td class="px-6 py-4 text-center text-gray-600">{{ exam.questions }}</td>
            <td class="px-6 py-4 text-center text-gray-600">{{ exam.attempts.toLocaleString() }}</td>
            <td class="px-6 py-4 text-center">
              <span :class="statusClass(exam.status)">
                {{ exam.status }}
              </span>
            </td>
            <td class="px-6 py-4 text-center text-gray-500 text-sm">{{ exam.last_modified }}</td>
            <td class="px-6 py-4 text-right">
              </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="fixed bottom-10 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center gap-4 text-sm">
      <span>To see your latest changes, refresh your preview</span>
      <button class="text-indigo-400 font-bold hover:text-indigo-300">Refresh</button>
    </div>
  </div>
</template>

<script setup>
import Header from '@/components/instructor/InstructorHeader.vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { LayoutDashboard, Users, BookOpenCheck, Database } from 'lucide-vue-next';


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