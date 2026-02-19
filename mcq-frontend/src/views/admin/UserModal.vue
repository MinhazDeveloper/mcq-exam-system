<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-[32px] w-full max-w-md p-8 shadow-2xl">
      <h3 class="text-xl font-bold text-slate-800 mb-6">{{ userData ? 'Edit User' : 'Add New User' }}</h3>
      
      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div>
          <label class="text-xs font-bold text-slate-400 uppercase">Full Name</label>
          <input v-model="formData.name" type="text" class="w-full mt-1 px-5 py-3 bg-slate-50 text-black rounded-2xl" required>
        </div>
        
        <div>
          <label class="text-xs font-bold text-slate-400 uppercase">Email Address</label>
          <input v-model="formData.email" type="email" class="w-full mt-1 px-5 py-3 bg-slate-50 text-black rounded-2xl" required>
        </div>

        <div>
          <label class="text-xs font-bold text-slate-400 uppercase">Role</label>
          <select v-model="formData.role" class="w-full mt-1 px-5 py-3 bg-slate-50 text-black rounded-2xl">
            <option value="student">Student</option>
            <option value="instructor">Instructor</option>
            <option value="admin">Admin</option>
          </select>
        </div>

        <div>
          <label class="text-xs font-bold text-slate-400 uppercase">Password</label>
          <!-- <input v-model="formData.password" type="password" class="w-full mt-1 px-5 py-3 bg-slate-50 text-black rounded-2xl" required> -->
          <input
            v-model="formData.password"
            type="password"
            class="w-full mt-1 px-5 py-3 bg-slate-50 text-black rounded-2xl"
            :required="!userData"
          />
        </div>

        <div class="flex gap-3 pt-4">
          <button @click="$emit('close')" type="button" class="flex-1 px-6 py-3 bg-slate-100 text-slate-500 font-bold rounded-2xl hover:bg-slate-200 transition">Cancel</button>
          <button type="submit" :disabled="submitting" class="flex-1 px-6 py-3 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition">
            {{ submitting ? 'Processing...' : (userData ? 'Update' : 'Save User') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

const props = defineProps({
  show: Boolean,
  userData: Object
})
const emit = defineEmits(['close', 'refresh'])

const authStore = useAuthStore()
const submitting = ref(false)
const formData = ref({ name: '', email: '', role: 'student', password: '' })

watch(() => props.userData, (newVal) => {
  if (newVal) {
    formData.value = { 
      ...newVal, 
      password: '' // password field empty for edit mode, unless admin wants to change it
    }
  } else {
    formData.value = { name: '', email: '', role: 'student', password: '' }
  }
}, { immediate: true })

const handleSubmit = async () => {
  try {
    submitting.value = true
    const isEdit = !!props.userData 
    
    const url = isEdit 
      ? `http://127.0.0.1:8000/api/admin/user/update/${props.userData.id}` 
      : 'http://127.0.0.1:8000/api/admin/user/store'
    
    // Payload
    const payload = { ...formData.value }  

    // Edit mode: remove empty password
    if (isEdit && !payload.password) delete payload.password
    
    const response = isEdit
      ? await axios.put(url, payload, {
          headers: { 
            'Authorization': `Bearer ${authStore.token}`,
            'Accept': 'application/json'
          }
        })
      : await axios.post(url, payload, {
          headers: { 
            'Authorization': `Bearer ${authStore.token}`,
            'Accept': 'application/json'
          }
        })

    // Success
    if (response.data.success || response.status === 200) {
      alert(isEdit ? 'User updated!' : 'New user added!')
      emit('refresh')
      emit('close')
    }
  } catch (error) {
    alert(error.response?.data?.message || 'something wrong')
  } finally {
    submitting.value = false
  }
}

</script>