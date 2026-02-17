<template>
  <div class="p-6 max-w-3xl mx-auto bg-gray-50 min-h-screen">
    <div v-if="submitted" class="bg-white p-10 rounded-xl shadow-lg text-center border-t-8 border-green-500">
      <h2 class="text-4xl font-black text-green-600 mb-2">Well Done!</h2>
      <p class="text-gray-500 mb-6">You have completed the exam.</p>
      
      <div class="grid grid-cols-2 gap-4 bg-gray-50 p-6 rounded-lg mb-6">
        <div>
          <p class="text-sm text-gray-500 uppercase">Total Questions</p>
          <p class="text-2xl font-bold">{{ questions.length }}</p>
        </div>
        <div>
          <p class="text-sm text-gray-500 uppercase">Obtained Marks</p>
          <p class="text-2xl font-bold text-blue-600">{{ obtainedMarks }}</p>
        </div>
      </div>

      <button @click="$router.back()" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-700 transition">
        Back to Exams
      </button>
    </div>

    <div v-else>
      <div class="mb-8 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Exam Paper</h1>
        <div class="bg-blue-100 text-blue-700 px-4 py-1 rounded-full text-sm font-bold">
          {{ questions.length }} Questions
        </div>
      </div>

      <div v-for="(q, index) in questions" :key="q.id" class="bg-white p-6 rounded-lg shadow-sm mb-6 border border-gray-200">
        <p class="text-lg font-bold text-gray-800 mb-4">
          <span class="text-blue-600">Q{{ index + 1 }}.</span> {{ q.question_text }}
        </p>

        <div class="space-y-3">
          <label 
            v-for="opt in q.options" :key="opt.id"
            :class="[
              'flex items-center p-4 border rounded-xl cursor-pointer transition-all',
              userAnswers[q.id] === opt.id ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500' : 'border-gray-200 hover:bg-gray-50'
            ]"
          >
            <input 
              type="radio" 
              :name="'question-' + q.id" 
              :value="opt.id"
              v-model="userAnswers[q.id]"
              class="hidden" 
            />
            <div :class="['w-5 h-5 rounded-full border-2 mr-3 flex items-center justify-center', userAnswers[q.id] === opt.id ? 'border-blue-500' : 'border-gray-300']">
              <div v-if="userAnswers[q.id] === opt.id" class="w-2.5 h-2.5 bg-blue-500 rounded-full"></div>
            </div>
            <span class="text-gray-700">{{ opt.option_text }}</span>
          </label>
        </div>
      </div>

      <button 
        @click="submitExam" 
        :disabled="Object.keys(userAnswers).length === 0"
        class="w-full bg-green-600 text-white py-4 rounded-xl font-bold text-xl hover:bg-green-700 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed mb-10 transition"
      >
        Submit Answer
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import apiClient from '@/api/axios';

const route = useRoute();
const examId = route.params.id; 

const questions = ref([]);
const userAnswers = ref({});
const submitted = ref(false);
const obtainedMarks = ref(0);

const fetchQuestions = async () => {
  try {
    const examId = route.params.id;
    const res = await apiClient.get(`/student/exams/${examId}/questions`);
    questions.value = res.data;
  } catch (error) {
    console.error("Error loading questions:", error);
  }
};

// result calculation
const submitExam = () => {
  if (!confirm("Are you sure you want to submit your answers?")) return;

  let score = 0;
  questions.value.forEach(question => {
    const selectedId = userAnswers.value[question.id];
    const correctOpt = question.options.find(o => o.is_correct == 1);
    
    if (selectedId === correctOpt?.id) {
      score++;
    }
  });

  obtainedMarks.value = score;
  submitted.value = true;
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

onMounted(fetchQuestions);
</script>