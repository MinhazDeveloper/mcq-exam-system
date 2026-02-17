<template>
  <div class="min-h-screen bg-[#F3F4F6] p-4 md:p-8 font-sans">
    <div class="max-w-4xl mx-auto">

      <div v-if="loading" class="flex flex-col items-center justify-center min-h-[400px]">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
        <p class="mt-4 text-gray-500 font-medium">Loading exam results...</p>
      </div>

      <div v-else-if="error" class="bg-red-50 border border-red-200 text-red-700 p-6 rounded-xl shadow-sm text-center">
        <h3 class="text-xl font-bold mb-3">Error!</h3>
        <p>{{ error }}</p>
        <button @click="$router.push('/student/dashboard')" class="mt-4 text-indigo-600 font-bold underline">
          Go to Dashboard
        </button>
      </div>

      <div v-else-if="result" class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:p-10 mb-8">
        <h1 class="text-3xl font-extrabold text-gray-800 mb-4 text-center">Exam Result: {{ result.exam_title }}</h1>
        <p class="text-gray-500 text-center mb-8">Submitted on: {{ result.submitted_at }}</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 text-center mb-8">
          <div class="bg-indigo-50 p-4 rounded-xl">
            <p class="text-sm text-indigo-600 font-medium">Total Questions</p>
            <p class="text-2xl font-bold text-indigo-800">{{ result.total_questions }}</p>
          </div>
          <div class="bg-green-50 p-4 rounded-xl">
            <p class="text-sm text-green-600 font-medium">Correct Answers</p>
            <p class="text-2xl font-bold text-green-800">{{ result.correct_answers }}</p>
          </div>
          <div class="bg-red-50 p-4 rounded-xl">
            <p class="text-sm text-red-600 font-medium">Wrong Answers</p>
            <p class="text-2xl font-bold text-red-800">{{ result.wrong_answers }}</p>
          </div>
          <div class="bg-yellow-50 p-4 rounded-xl">
            <p class="text-sm text-yellow-600 font-medium">Your Score</p>
            <p class="text-2xl font-bold text-yellow-800">{{ result.obtained_marks }}</p>
          </div>
        </div>

        <div class="flex justify-center gap-4 mt-8">
          <button 
            @click="showReview = !showReview"
            :class="[
              'px-6 py-3 rounded-xl font-bold transition-all',
              showReview 
                ? 'bg-gray-200 text-gray-700 hover:bg-gray-300' 
                : 'bg-indigo-600 text-white shadow-lg shadow-indigo-200 hover:bg-indigo-700'
            ]"
          >
            {{ showReview ? 'Hide Review' : 'Review Answers' }}
          </button>
          <button 
            @click="$router.push('/student/dashboard')"
            class="px-6 py-3 text-indigo-600 font-bold border border-indigo-200 rounded-xl hover:bg-indigo-50 transition-colors"
          >
            Back to Dashboard
          </button>
        </div>
      </div>

      <div v-if="showReview && result && result.answers" class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:p-10 mt-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Detailed Answer Review</h2>
        
        <div v-for="(answer, qIndex) in result.answers" :key="qIndex" class="mb-10 p-6 border-b border-gray-100 last:border-b-0">
          <h3 class="text-lg font-semibold text-gray-800 mb-4">
            Question {{ qIndex + 1 }}: {{ answer.question }}
          </h3>

          <div class="space-y-3">
            <div 
              v-for="option in answer.options" 
              :key="option.id"
              :class="[
                'flex items-center p-3 rounded-lg border-2 transition-colors duration-200',
                // User selected this option AND it was correct
                (answer.selected_option_id === option.id && option.is_correct) ? 'border-green-500 bg-green-50' :
                // User selected this option AND it was wrong
                (answer.selected_option_id === option.id && !option.is_correct) ? 'border-red-500 bg-red-50' :
                // This option was the correct one but user didn't select it
                (option.is_correct && answer.selected_option_id !== option.id) ? 'border-green-300 bg-green-100' :
                // Default style for unselected, wrong options
                'border-gray-200 text-gray-700'
              ]"
            >
              <div :class="[
                'w-4 h-4 rounded-full border mr-3 flex items-center justify-center',
                (answer.selected_option_id === option.id && option.is_correct) ? 'border-green-600 bg-green-600' :
                (answer.selected_option_id === option.id && !option.is_correct) ? 'border-red-600 bg-red-600' :
                (option.is_correct && answer.selected_option_id !== option.id) ? 'border-green-400 bg-white' :
                'border-gray-400 bg-white'
              ]">
                <div v-if="(answer.selected_option_id === option.id && (option.is_correct || !option.is_correct))" class="w-2 h-2 bg-white rounded-full"></div>
                <div v-else-if="option.is_correct" class="w-2 h-2 bg-green-500 rounded-full"></div>
              </div>
              <span class="font-medium" :class="{ 'text-green-800': option.is_correct && answer.selected_option_id !== option.id }">
                {{ option.option_text }}
              </span>
            </div>
          </div>
          <p v-if="answer.selected_option_id === null" class="mt-3 text-sm text-yellow-600 font-medium">
            You did not answer this question.
          </p>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const router = useRouter();
const submissionId = route.params.id;

const loading = ref(true);
const error = ref(null);
const result = ref(null);
const showReview = ref(false);

const fetchResult = async () => {
  try {
    loading.value = true;
    const token = localStorage.getItem('token');
    if (!token) {
      router.push('/login');
      return;
    }

    const response = await axios.get(`http://127.0.0.1:8000/api/student/exam-result/${submissionId}`, {
      headers: { 'Authorization': `Bearer ${token}` }
    });

    if (response.data.success) {
      result.value = response.data.data;
      error.value = null;
    } else {
      error.value = response.data.message || 'Failed to fetch result.';
    }
  } catch (err) {
    console.error("Error fetching result:", err);
    error.value = 'Failed to fetch result. Please try again or check your internet connection.';
    if (err.response && err.response.status === 404) {
      error.value = 'Result not found or you do not have permission to view it.';
    } else if (err.response && err.response.status === 401) {
      router.push('/login');
    }
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  if (submissionId) {
    fetchResult();
  } else {
    error.value = 'No submission ID provided. Cannot display results.';
    loading.value = false;
  }
});
</script>

<style scoped>
/* Custom styles if needed, but Tailwind CSS handles most of it */
</style>