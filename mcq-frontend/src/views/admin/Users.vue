<template>
  <!-- strt -->
  <div class="space-y-6">
    <div class="flex justify-between items-center">
      <div>
        <h6 class="text-[22px] font-bold text-[#0F172A] tracking-tight">User Management</h6>
      </div>
      <div class="flex gap-3">
        <button @click="isModalOpen = true; selectedUser = null" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 rounded-xl text-sm font-bold text-white hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition">
          <span>+</span> Add User
        </button>
      </div>
    </div>
    
    <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
      <div class="flex flex-col md:flex-row justify-between gap-4">
        <div class="flex bg-slate-50 p-1.5 rounded-2xl w-fit">
          <button 
            v-for="tab in ['admins', 'instructors', 'students']" 
            :key="tab"
            @click="activeTab = tab"
            :class="activeTab === tab ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-400'"
            class="px-6 py-2 rounded-xl text-sm font-bold transition-all capitalize"
          >
            {{ tab }}
          </button>
        </div>

        <div class="relative group">
          <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
          <input type="text" placeholder="Search by name or email..." class="pl-11 pr-4 py-2.5 bg-slate-50 border-none rounded-2xl text-sm w-full md:w-72 focus:ring-2 focus:ring-indigo-100 transition">
        </div>
      </div>
    </div>

    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="text-slate-400 text-[11px] uppercase tracking-widest border-b border-slate-50">
            <th class="py-5 px-8 font-semibold">User</th>
            <th class="py-5 font-semibold">Role</th>
            <th class="py-5 font-semibold">Last Active</th>
            <th class="py-5 px-8 text-right font-semibold">Actions</th>
          </tr>
        </thead>
        
        <tbody class="divide-y divide-slate-50">
          <tr v-for="user in filteredUsers" :key="user.id" class="group hover:bg-slate-50/50 transition-colors">
            <td class="py-4 px-8 flex items-center gap-3">
              <img :src="user.avatar || 'https://ui-avatars.com/api/?name='+user.name" class="w-10 h-10 rounded-full" />
              <div>
                  <div class="text-sm font-bold text-slate-700">{{ user.name }}</div>
                  <div class="text-[11px] text-slate-400">{{ user.email }}</div>
              </div>
            </td>

            <td class="py-4 text-sm text-slate-600 font-medium capitalize">{{ user.role }}</td>

            <td class="py-4 text-xs text-slate-400 font-medium italic">
              {{ getTimeAgo(user.last_login_at || user.updated_at) }}
            </td>

            <td class="py-4 px-8 text-right">
              <div class="flex justify-end gap-5 transition-all duration-300">
                <button 
                  @click="openEditModal(user)" 
                  class="text-[14px] font-bold text-indigo-600 hover:text-indigo-800 transition-colors"
                >
                  Edit
                </button>

                <button 
                  @click="deleteUser(user.id)"
                  class="text-[14px] font-bold text-rose-500 hover:text-rose-700 transition-colors"
                >
                  Delete
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    
    <UserModal 
      :show="isModalOpen" 
      :user-data="selectedUser" 
      @close="closeModal" 
      @refresh="fetchUsers" 
    />
    
  </div>
</template>

<script setup>

import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'
import UserModal from './UserModal.vue'

const authStore = useAuthStore()
const activeTab = ref('students')
const users = ref([]) 
const loading = ref(true)
const isModalOpen = ref(false)
const selectedUser = ref(null)

const getTimeAgo = (dateString) => {
  if (!dateString) return 'Never';
  
  const date = new Date(dateString);
  const now = new Date();
  const diffInSeconds = Math.floor((now - date) / 1000);

  if (diffInSeconds < 60) return 'Just now';
  
  const diffInMinutes = Math.floor(diffInSeconds / 60);
  if (diffInMinutes < 60) return `${diffInMinutes} mins ago`;
  
  const diffInHours = Math.floor(diffInMinutes / 60);
  if (diffInHours < 24) return `${diffInHours} hours ago`;
  
  const diffInDays = Math.floor(diffInHours / 24);
  return `${diffInDays} days ago`;
}

const openEditModal = (user) => {
  selectedUser.value = { ...user }
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
  selectedUser.value = null
}

const fetchUsers = async () => {
  try {
    loading.value = true
    const token = authStore.token 

    const response = await axios.get('http://127.0.0.1:8000/api/admin/user/all', {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    })
    if (response.data && response.data.data) {
        users.value = response.data.data 
    } else {
        users.value = Array.isArray(response.data) ? response.data : []
    }

  } catch (error) {
    if (error.response && error.response.status === 401) {
      console.error("Unauthorized");
    }
  } finally {
    loading.value = false
  }
}

// function call after page load
onMounted(() => {
  fetchUsers()
})

const filteredUsers = computed(() => {
  if (!users.value || users.value.length === 0) return []

  return users.value.filter(u => {
    if (u && u.role) {
      const tabRole = activeTab.value.toLowerCase().slice(0, -1)
      return u.role.toLowerCase().includes(tabRole)
    }
    return false
  })
})

const deleteUser = async (id) => {

  if (!confirm('Are you sure you want to delete this user?')) return;

  try {
    const token = authStore.token;
    const response = await axios.delete(`http://127.0.0.1:8000/api/admin/user/delete/${id}`, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    });

    if (response.data.success || response.status === 200) {
      alert('User deleted successfully!');
      fetchUsers();
    }
  } catch (error) {
    console.error("Error deleting user:", error);
    alert(error.response?.data?.message || 'Failed to delete user');
  }
};

</script>