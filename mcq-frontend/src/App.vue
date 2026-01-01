<script setup>
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();

if (authStore.token && !authStore.user) {
  authStore.fetchUser();
}
</script>

<template>
  <router-view />
</template>
