<template>
  <div class="p-8 bg-gray-50 min-h-screen ">
    <div class="mb-8">
      <!-- <h1 class="text-2xl font-extrabold text-[#111827]">Enrolled Exams</h1> -->
      <h6 class="text-[22px] font-bold text-[#0F172A] tracking-tight">Enrolled Exams</h6>
      <p class="text-gray-500 mt-1">Manage and attend your scheduled examinations.</p>
    </div>

    <div v-if="loading" class="flex justify-center items-center h-64">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      
      <div 
        v-for="exam in exams" 
        :key="exam.id" 
        class="bg-white rounded-[24px] p-8 border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative"
      >
        <div class="flex justify-between items-start mb-6">
          <span class="text-[12px] font-bold tracking-widest text-[#4F46E5] uppercase">
            {{ exam.subject || 'Subject' }}
          </span>
          
          <!-- <span :class="statusBadgeClass(exam.status)">
            <span v-if="exam.status === 'live'" class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse mr-1.5"></span>
            {{ capitalize(exam.status) }}
          </span> -->
        </div>

        <h2 class="text-xl font-bold text-gray-900 leading-tight mb-6">
          {{ exam.title }}
        </h2>

        <div class="space-y-3 mb-8">
          <div class="flex items-center text-gray-500 text-sm gap-3">
            <span>🕒</span> {{ exam.duration_minutes }} minutes
          </div>
          <div class="flex items-center text-gray-500 text-sm gap-3">
            <span>📋</span> {{ exam.questions_count }} Questions
          </div>
          <div class="flex items-center text-gray-500 text-sm gap-3">
            <span>📅</span> {{ formatDate(exam.updated_at) }}
          </div>
        </div>

        <button 
          v-if="exam.status === 'live'"
          @click="$router.push(`/student/exam/${exam.id}`)"
          class="w-full py-4 bg-[#4F46E5] text-white rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-[#4338CA] transition shadow-[0_10px_20px_-5px_rgba(79,70,229,0.4)]"
        >
          Start Exam <span>→</span>
        </button>

        <button 
          v-else-if="exam.status === 'upcoming'"
          disabled
          class="w-full py-4 bg-gray-50 text-gray-400 rounded-xl font-bold cursor-not-allowed border border-gray-100"
        >
          Wait to Start
        </button>

        <button 
          v-else
          @click="$router.push(`/student/exam/${exam.id}`)"
          class="w-full py-4 bg-gradient-to-r from-indigo-600 to-purple-600 
                text-white rounded-xl font-bold 
                hover:from-indigo-700 hover:to-purple-700 
                transition duration-300 shadow-md"
        >
          Start Exam →
        </button>


      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const exams = ref([]);
const loading = ref(true);

const fetchExams = async () => {
  try {
    loading.value = true;
    const token = localStorage.getItem('token');
    const response = await axios.get('http://127.0.0.1:8000/api/student/exams', {
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

// Helper Functions for Styling
const statusBadgeClass = (status) => {
  const base = "flex items-center px-3 py-1 rounded-full text-[11px] font-bold border ";
  if (status === 'live') return base + "bg-red-50 text-red-500 border-red-100";
  if (status === 'upcoming') return base + "bg-blue-50 text-blue-500 border-blue-100";
  return base + "bg-green-50 text-green-500 border-green-100";
};

// const capitalize = (str) => str.charAt(0).toUpperCase() + str.slice(1);
const capitalize = (str) => {
  if (!str) return '';
  return str.charAt(0).toUpperCase() + str.slice(1);
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString() + ' • ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};
</script>