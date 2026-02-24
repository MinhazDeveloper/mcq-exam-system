<template>
  <div class="p-8 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-6xl mx-auto">
      <div class="mb-6 flex justify-between items-center">
        <h6 class="text-[22px] font-bold text-[#0F172A] tracking-tight">Student Exam History</h6>
        <div class="text-sm text-gray-500">Total Submissions: {{ results.length }}</div>
      </div>

      <div v-if="loading" class="text-center py-10">
        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-600 mx-auto"></div>
        <p class="mt-4 text-gray-500 font-medium">Loading history, please wait...</p>
      </div>

      <div v-else class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="border-b border-gray-100 bg-gray-50/50">
              <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Student</th>
              <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Exam Title</th>
              <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Score</th>
              <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Status</th>
              <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Submitted At</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="result in results" :key="result.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-5">
                <div class="font-bold text-gray-600">{{ result.student_name }}</div>
                <div class="text-xs text-gray-400">{{ result.student_email }}</div>
              </td>
              
              <td class="px-6 py-5 text-gray-600 ">
                {{ result.exam_title }}
              </td>

              <td class="px-6 py-5">
                <div class="flex flex-col items-center gap-1">
                  <span class="text-sm font-bold text-gray-700">
                    {{ result.obtained_marks }} / {{ result.total_marks }}
                  </span>
                  <div class="w-24 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div 
                      :class="result.status === 'Pass' ? 'bg-green-500' : 'bg-red-500'" 
                      class="h-full rounded-full transition-all duration-500" 
                      :style="{ width: (result.obtained_marks / result.total_marks * 100) + '%' }"
                    ></div>
                  </div>
                </div>
              </td>

              <td class="px-6 py-5 text-center">
                <span :class="[
                  'px-3 py-1 rounded-full text-xs font-bold border',
                  result.status === 'Pass' 
                    ? 'bg-green-50 text-green-600 border-green-100' 
                    : 'bg-red-50 text-red-600 border-red-100'
                ]">
                  {{ result.status }}
                </span>
              </td>

              <td class="px-6 py-5 text-right text-gray-500 text-sm">
                {{ result.submitted_at }}
              </td>
            </tr>

            <tr v-if="results.length === 0">
                <td colspan="5" class="text-center py-20">
                  <div class="text-gray-400 text-lg">No exam history found.</div>
                </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const results = ref([]);
const loading = ref(true);

const fetchHistory = async () => {
  try {
    loading.value = true;
    const token = localStorage.getItem('token');
    const response = await axios.get('http://127.0.0.1:8000/api/admin/exam-history', {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    
    results.value = response.data.data || [];
  } catch (error) {
    console.error("Error fetching history:", error);
    alert("Failed to load exam history.");
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchHistory();
});
</script>