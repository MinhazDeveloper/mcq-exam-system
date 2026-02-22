<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-[20px] border border-slate-100 shadow-sm flex flex-col justify-between h-[160px]">
      <div class="flex justify-between items-start">
        <div>
          <p class="text-[15px] font-medium text-slate-500">Total Exams66</p>
          <h3 class="text-[32px] font-bold text-slate-900 leading-tight mt-1">
            {{ stats.total_exams }}
          </h3>
        </div>
        <div class="p-3 bg-slate-50 rounded-xl">
          <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
      </div>
    </div>

    <div class="bg-white p-6 rounded-[20px] border border-slate-100 shadow-sm flex flex-col justify-between h-[160px]">
      <div class="flex justify-between items-start">
        <div>
          <p class="text-[15px] font-medium text-slate-500">Active Students</p>
          <h3 class="text-[32px] font-bold text-slate-900 leading-tight mt-1">
            {{ stats.active_students }}
          </h3>
        </div>
        <div class="p-3 bg-indigo-50 rounded-xl">
          <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const stats = ref({
  total_exams: 0,
  active_students: 0
});

const loading = ref(true);

const fetchDashboardStats = async () => {
  try {
    const token = localStorage.getItem('token');
    const response = await axios.get('http://127.0.0.1:8000/api/instructor/stats', {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    if (response.data.success) {
      stats.value = response.data.data;
    }
  } catch (error) {
    console.error("Stats fetch error:", error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchDashboardStats();
});
</script>