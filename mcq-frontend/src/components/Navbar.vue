<template>
  <nav
    v-if="auth.isAuthenticated"
    class="bg-slate-900 text-white px-6 py-4 shadow-lg"
  >
    <div class="flex items-center justify-between">

      <!-- Left: Logo / Brand -->
      <div class="flex items-center gap-2">
        <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center font-bold">
          M
        </div>
        <span class="text-lg font-semibold tracking-wide">
          MCQ Exam System
        </span>
      </div>

      <!-- Right: Profile -->
      <div class="relative">
        <button
          @click="toggleDropdown"
          class="flex items-center gap-2 px-3 py-2 rounded-full hover:bg-slate-700 transition"
        >
          <span class="text-sm font-medium">Admin</span>
          <div class="w-9 h-9 bg-slate-600 rounded-full flex items-center justify-center text-lg">
            👤
          </div>
        </button>

        <!-- Dropdown -->
        <transition name="fade">
          <ul
            v-if="isOpen"
            class="absolute right-0 mt-3 w-40 bg-white text-gray-700 rounded-lg shadow-lg overflow-hidden"
          >
            <li>
              <button
                class="w-full text-left px-4 py-2 hover:bg-gray-100 transition"
              >
                Profile
              </button>
            </li>
            <li>
              <button
                @click="logout"
                class="w-full text-left px-4 py-2 hover:bg-red-50 text-red-600 font-semibold transition"
              >
                Logout
              </button>
            </li>
          </ul>
        </transition>
      </div>

    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
// import axios from '@/api/axios'

const isOpen = ref(false)
const router = useRouter()
const auth = useAuthStore()

const toggleDropdown = () => {
  isOpen.value = !isOpen.value
}
const logout = async () => {
  await auth.logout()
  isOpen.value = false
  router.replace('/login')
}
// const logout = async () => {
//   try {
//     const token = localStorage.getItem('token')

//     await axios.post('/auth/logout', {}, {
//       headers: {
//         Authorization: `Bearer ${token}`
//       }
//     })

//     localStorage.removeItem('token')
//     router.push('/login')
//   } catch (error) {
//     console.error('Logout failed', error)
//   }
// }

</script>
