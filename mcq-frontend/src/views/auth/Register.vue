<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
      <h2 class="text-2xl font-bold text-center mb-6 text-black">
        Create Account
      </h2>

      <form @submit.prevent="handleRegister" class="space-y-4">
        <!-- Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Name</label>
          <input
            v-model="form.name"
            type="text"
            required
            class="mt-1 w-full border px-3 py-2 rounded text-black"
            placeholder="Your name"
          />
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Email</label>
          <input
            v-model="form.email"
            type="email"
            required
            class="mt-1 w-full border px-3 py-2 rounded text-black"
            placeholder="you@example.com"
          />
        </div>

        <!-- Password -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Password</label>
          <input
            v-model="form.password"
            type="password"
            required
            class="mt-1 w-full border px-3 py-2 rounded text-black"
            placeholder="********"
          />
        </div>
        <!-- Password Confirmation -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Password Confirmation</label>
          <input
            v-model="form.password_confirmation"
            type="password"
            required
            class="mt-1 w-full border px-3 py-2 rounded text-black"
            placeholder="********"
          />
        </div>

        <!-- Submit -->
        <button
          type="submit"
          :disabled="loading"
          class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700 disabled:opacity-50"
        >
          {{ loading ? 'Registering...' : 'Register' }}
        </button>
      </form>

      <p class="mt-4 text-center text-sm text-gray-600">
        Already have an account?
        <router-link to="/" class="text-indigo-600 font-medium">
          Login
        </router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import apiClient from '@/api/axios'
import Swal from 'sweetalert2'

const router = useRouter()
const loading = ref(false)

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',

})

const handleRegister = async () => {
  loading.value = true
  try {
    await apiClient.post('/auth/register', form)
    await Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: 'Registration successful. Please login to continue.',
      timer: 2000,
      showConfirmButton: false,
      customClass: {
        popup: 'rounded-[24px]',
      }
    })
    router.push('/')
  } catch (error) {
    console.error('Registration Error:', error.response?.data)

    const errorData = error.response?.data
    let errorMessage = errorData?.message || 'Registration failed'

    if (errorData?.errors) {
      errorMessage = Object.values(errorData.errors).flat().join('<br>')
    }

    Swal.fire({
      icon: 'error',
      title: 'Registration Failed',
      html: errorMessage,
      confirmButtonColor: '#4F46E5',
      customClass: {
        popup: 'rounded-[24px]',
        confirmButton: 'rounded-xl px-6 py-2'
      }
    })
  } finally {
    loading.value = false
  }
}
</script>
