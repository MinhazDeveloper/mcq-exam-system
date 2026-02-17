<template>
  <div class="p-8 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-6xl mx-auto">
      <div class="mb-6">
        <h6 class="text-[22px] font-bold text-[#0F172A] tracking-tight">Exam History</h6>
        
        <p class="text-gray-500">View your past results and performance.</p>
      </div>

      <div v-if="loading" class="text-center py-10">
          <p class="text-gray-500">Loading your history...</p>
      </div>

      <div v-else class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="border-b border-gray-100 bg-gray-50/50">
              <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Exam Name</th>
              <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Date</th>
              <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Score</th>
              <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Status</th>
              <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="item in history" :key="item.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-5 font-semibold text-gray-800">{{ item.exam.title }}</td>
              <td class="px-6 py-5 text-gray-400 text-sm">{{ formatDate(item.created_at) }}</td>
              <td class="px-6 py-5">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-bold text-gray-700">
                    {{ item.obtained_marks }}/{{ item.total_questions }}
                    </span>

                    <div class="w-24 h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div 
                        :class="item.obtained_marks >= (item.exam.pass_marks || 1) ? 'bg-green-500' : 'bg-red-500'" 
                        class="h-full rounded-full transition-all duration-500" 
                        :style="{ width: calculatePercentage(item.obtained_marks, item.total_questions) + '%' }"
                    ></div>
                    </div>
                </div>
              </td>
              <!-- <td class="px-6 py-5">
                <div class="flex items-center gap-3">
                  <span class="text-sm font-bold text-gray-700">
                    {{ item.obtained_marks }}/{{ item.total_questions }}
                  </span>
                  <div class="w-24 h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div 
                      :class="isPassed(item) ? 'bg-green-500' : 'bg-red-500'" 
                      class="h-full rounded-full" 
                      :style="{ width: (item.obtained_marks / item.total_questions) * 100 + '%' }"
                    ></div>
                  </div>
                </div>
              </td> -->
              <td class="px-6 py-5 text-center">
                <span 
                  v-if="isPassed(item)"
                  class="px-3 py-1 text-xs font-bold text-green-600 bg-green-50 border border-green-100 rounded-full"
                >Pass</span>
                <span 
                  v-else
                  class="px-3 py-1 text-xs font-bold text-red-600 bg-red-50 border border-red-100 rounded-full"
                >Fail</span>
              </td>
              <td class="px-6 py-5 text-right">
                <button 
                  @click="$router.push({ name: 'ExamResult', params: { id: item.id } })"
                  class="text-indigo-600 font-bold text-sm hover:underline flex items-center justify-end gap-1 ml-auto"
                >
                  View Details <i class="far fa-eye text-xs"></i>
                </button>
              </td>
            </tr>
            <tr v-if="history.length === 0">
                <td colspan="5" class="text-center py-10 text-gray-500">No exam history found.</td>
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

const history = ref([]);
const loading = ref(true);

// পাস মার্কস চেক করার লজিক (আপনি চাইলে ব্যাকএন্ড থেকেও সরাসরি স্ট্যাটাস পাঠাতে পারেন)
const isPassed = (item) => {
    // যদি প্রাপ্ত মার্কস পাস মার্কসের সমান বা বেশি হয়
    return item.obtained_marks >= (item.exam.pass_marks || 1);
};

// ডেট ফরম্যাট করার জন্য
const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
};

const fetchHistory = async () => {
    try {
        const token = localStorage.getItem('token');
        const response = await axios.get('http://127.0.0.1:8000/api/student/exam-history', {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        history.value = response.data.data;
    } catch (error) {
        console.error("History fetch error:", error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchHistory();
});

const calculatePercentage = (obtained, total) => {
  if (!total || total === 0) return 0; // ০ দিয়ে ভাগ করা এড়াতে
  const percentage = (obtained / total) * 100;
  return percentage > 100 ? 100 : percentage.toFixed(0); // সর্বোচ্চ ১০০% পর্যন্ত দেখাবে
};

</script>