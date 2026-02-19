<template>
  <div class="p-8 bg-[#F8FAFC] min-h-screen font-sans text-slate-700">
    <div class="flex justify-between items-center mb-8">
      <h2 class="text-2xl font-bold text-slate-900">Exam Details</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
       <div v-for="exam in exams" :key="exam.id" class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative group">
        <div class="flex justify-between items-start mb-4">
          <span :class="statusClass(exam.subject)" class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider">
            {{ exam.subject }}
          </span>
          <button class="text-slate-300 hover:text-slate-600 transition-colors text-xl font-bold">⋮</button>
        </div>

        <h3 class="text-lg font-bold text-slate-800 mb-4 line-clamp-1 leading-tight">
          {{ exam.title }}
        </h3>

        <div class="space-y-2 mb-6">
          <div class="flex items-center gap-3 text-slate-400 text-sm">
            <span>🕒</span>
            <span class="font-medium">{{ exam.duration_minutes }} minutes</span>
          </div>
          <div class="flex items-center gap-3 text-slate-400 text-sm">
            <span>📅</span>
            <span class="font-medium">{{ exam.questions_count }}</span>
          </div>
          <div class="flex items-center gap-3 text-slate-400 text-sm">
            <span>📅</span> {{ formatDate(exam.updated_at) }}
          </div>
        </div>
        
        <hr class="border-slate-50 mb-6">

      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();
const activeTab = ref('All');
const exams = ref([]); 
const loading = ref(true);

const fetchExams = async () => {
  try {
    loading.value = true;
    const token = authStore.token;

    const response = await axios.get('http://127.0.0.1:8000/api/admin/exams', {
      headers: { 
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    });

    if (response.data.success) {
      exams.value = response.data.data;
    }
  } catch (error) {
    console.error("Error fetching exams:", error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchExams();
});

const statusClass = (status) => {
  const s = status?.toLowerCase();
  if (s === 'ongoing') return 'bg-green-50 text-green-500';
  if (s === 'upcoming') return 'bg-amber-50 text-amber-500';
  if (s === 'completed') return 'bg-blue-50 text-blue-500';
  return 'bg-slate-100 text-slate-500';
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString() + ' • ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

</script>