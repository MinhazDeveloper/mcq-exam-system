import { defineStore } from 'pinia';
import apiClient from '@/api/axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('token'),
    user: JSON.parse(localStorage.getItem('user')),
  }),

  getters: {
    isAdmin: (state) => state.user?.role === 'admin',
    isAuthenticated: (state) => !!state.token,
  },

  actions: {
    setAuth(token, user) {
      this.token = token;
      this.user = user;
      localStorage.setItem('token', token);
      localStorage.setItem('user', JSON.stringify(user));
    },

    async logout() {
      try {
        await apiClient.post('/auth/logout')
      } catch (error) {
        
      }

      this.token = null
      this.user = null

      localStorage.removeItem('token')
      localStorage.removeItem('user')
    },

    async fetchUser() {
      const res = await apiClient.get('/user');
      this.user = res.data;
    }
  }
});
