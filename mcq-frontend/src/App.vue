<script setup>
import { useAuthStore } from '@/stores/auth';
import Navbar from '@/components/Navbar.vue';

const authStore = useAuthStore();

if (authStore.token && !authStore.user) {
  authStore.fetchUser();
}
</script>

<template>
  <Navbar />
  <router-view />
</template>
