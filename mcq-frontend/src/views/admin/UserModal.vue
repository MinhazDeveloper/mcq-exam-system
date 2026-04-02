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
import Swal from 'sweetalert2';
import api from "@/services/api";

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  userData: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'refresh'])

const submitting = ref(false)
const formData = ref({ name: '', email: '', role: 'student', password: '' })

const resetForm = () => {
  formData.value = { name: '', email: '', role: 'student', password: '' }
}

watch(() => props.show, (isVisible) => {
  if (isVisible) {
    if (props.userData) {
      formData.value = { ...props.userData, password: '' }
    } else {
      resetForm()
    }
  }
})

const handleSubmit = async () => {
  try {
    submitting.value = true
    const isEdit = !!props.userData 
    
    const url = isEdit 
      ? `/admin/user/update/${props.userData.id}` 
      : '/admin/user/store';

    // Payload
    const payload = { ...formData.value }  

    // Edit mode: remove empty password
    if (isEdit && !payload.password) delete payload.password

    const response = isEdit 
      ? await api.put(url, payload) 
      : await api.post(url, payload);

   
    // Success
    if (response.data.success || response.status === 200 || response.status === 201) {
      Swal.fire({
        icon: 'success',
        title: isEdit ? 'User Updated!' : 'User Added!',
        text: isEdit ? 'The user details have been updated successfully.' : 'New user has been created.',
        timer: 2000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end',
        background: '#ffffff',
        iconColor: '#4F46E5', 
      });
      emit('refresh');
      emit('close');
      resetForm();
    }
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Action Failed',
      text: error.response?.data?.message || 'Something went wrong. Please try again.',
      confirmButtonColor: '#4F46E5',
      customClass: {
        popup: 'rounded-[24px]'
      }
    });
  } finally {
    submitting.value = false
  }
}

</script>