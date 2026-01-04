<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
      <h2 class="text-2xl font-bold text-center text-gray-800 mb-8 text-black">Login to Your Account</h2>
      
      <form @submit.prevent="handleLogin" class="space-y-6">
        <div>
          <label class="block text-sm font-medium text-gray-700">Email Address</label>
          <input 
            v-model="form.email"
            type="email" 
            required 
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-black"
            placeholder="you@example.com"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Password</label>
          <input 
            v-model="form.password"
            type="password" 
            required 
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-black"
            placeholder="••••••••"
          />
        </div>

        <button 
          :disabled="isLoading"
          type="submit" 
          class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none disabled:opacity-50"
        >
          {{ isLoading ? 'Logging in...' : 'Sign In' }}
        </button>
      </form>

      <div class="mt-6">
        <div class="relative">
          <div class="absolute inset-0 flex items-center"><span class="w-full border-t border-gray-300"></span></div>
          <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">Or continue with</span>
          </div>
        </div>

        <!-- <div class="mt-6 flex justify-center">
          <GoogleSignInButton
            @success="handleGoogleSuccess"
            @error="handleGoogleError"
          />
        </div> -->
      </div>

      <p class="mt-8 text-center text-sm text-gray-600">
        Don't have an account? 
        <router-link to="/register" class="font-medium text-indigo-600 hover:text-indigo-500">Register here</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import apiClient from '@/api/axios';
import { GoogleSignInButton } from 'vue3-google-signin';

const router = useRouter();
const authStore = useAuthStore();

const form = reactive({
  email: '',
  password: ''
});

const isLoading = ref(false);

const handleLogin = async () => {
  isLoading.value = true;
  try {
    const response = await apiClient.post('/auth/login', form);
    const { token, user } = response.data;
    
    authStore.setAuth(token, user);

    router.push('/dashboard');
  } catch (error) {
    // alert(error.response?.data?.message || 'Login failed. Please try again.');
    
    console.log('FULL ERROR:', error);
    console.log('ERROR RESPONSE:', error.response);
    console.log('ERROR DATA:', error.response?.data);
    console.log('STATUS:', error.response?.status);

    alert(error.response?.data?.message || 'Login failed. Please try again.');
    
  } finally {
    isLoading.value = false;
  }
};

const handleGoogleSuccess = async (response) => {
  try {
    const googleToken = response.credential;
    const res = await apiClient.post('/auth/google', { token: googleToken });
    
    authStore.setToken(res.data.token);
    authStore.setUser(res.data.user);
    
    router.push('/dashboard');
  } catch (error) {
    console.error("Google Login Error:", error);
  }
};

const handleGoogleError = () => {
  alert("Google Sign-In was unsuccessful.");
};
</script>
