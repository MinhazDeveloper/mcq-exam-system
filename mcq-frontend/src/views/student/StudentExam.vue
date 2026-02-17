<template>
  <div class="min-h-screen bg-[#F3F4F6] p-4 md:p-8 font-sans">
    
    <div v-if="loading" class="flex flex-col items-center justify-center min-h-[400px]">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
      <p class="mt-4 text-gray-500 font-medium">Loading questions, please wait...</p>
    </div>
    
    <div v-else-if="questions.length > 0" class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-6">
      <div class="flex-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-10 min-h-[500px] flex flex-col">
          <div class="flex justify-between items-center mb-8">
            <span class="text-[12px] font-bold text-gray-400 uppercase tracking-widest">
              Question {{ currentQuestionIndex + 1 }} of {{ totalQuestions }}
            </span>
            <!-- <button 
              @click="toggleMarkForReview"
              :class="['text-sm font-medium flex items-center gap-2 transition-colors', markedForReview.has(currentQuestionIndex) ? 'text-yellow-600' : 'text-gray-500 hover:text-indigo-600']"
            >
              <span>🚩</span> {{ markedForReview.has(currentQuestionIndex) ? 'Marked' : 'Mark for Review' }}
            </button> -->
          </div>

          <h2 class="text-xl font-bold text-gray-800 mb-8 leading-relaxed">
            Question {{ currentQuestionIndex + 1 }}: {{ questions[currentQuestionIndex].question_text }}
          </h2>

          <div class="space-y-4 flex-1">
            <div 
              v-for="(option, index) in questions[currentQuestionIndex].options" 
              :key="option.id"
              @click="selectOption(index)"
              :class="[
                'flex items-center p-4 border rounded-xl cursor-pointer transition-all duration-200',
                selectedOptions[currentQuestionIndex] === index 
                  ? 'border-indigo-500 bg-indigo-50/30' 
                  : 'border-gray-100 hover:border-indigo-300 hover:bg-gray-50'
              ]"
            >
              <div :class="[
                'w-5 h-5 rounded-full border-2 mr-4 flex items-center justify-center transition-colors',
                selectedOptions[currentQuestionIndex] === index ? 'border-indigo-500' : 'border-gray-300'
              ]">
                <div v-if="selectedOptions[currentQuestionIndex] === index" class="w-2.5 h-2.5 bg-indigo-500 rounded-full"></div>
              </div>
              <span class="text-gray-700 font-medium">{{ option.option_text }}</span>
            </div>
          </div>

          <div class="mt-10 pt-6 border-t border-gray-100 flex justify-between items-center">
            <button 
              @click="prevQuestion"
              :disabled="currentQuestionIndex === 0"
              class="px-6 py-2.5 text-indigo-600 font-bold disabled:opacity-30 flex items-center gap-2"
            >
              ‹ Previous
            </button>
            <button 
              @click="nextQuestion"
              class="px-10 py-3 bg-[#4F46E5] text-white rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all flex items-center gap-2"
            >
              {{ currentQuestionIndex === totalQuestions - 1 ? 'Finish' : 'Next' }} ›
            </button>
          </div>
        </div>
      </div>
      
      <div class="w-full lg:w-80 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col items-center">
          <div class="flex items-center gap-2 mb-2">
            <span class="text-gray-400">🕒</span>
            <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Time Remaining</span>
          </div>
          <div :class="[
            'text-3xl font-mono font-bold transition-colors duration-500',
            timeLeft < 60 ? 'text-red-500 animate-pulse' : 'text-slate-800'
          ]">
            {{ formattedTime }}
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-8">
          <h3 class="font-bold text-gray-800 mb-6">Question Navigator</h3>
          <div class="grid grid-cols-5 gap-3 mb-8">
            <button 
              v-for="n in totalQuestions" 
              :key="n"
              @click="goToQuestion(n-1)"
              :class="['w-full aspect-square rounded-lg flex items-center justify-center text-sm font-bold border transition-all', getQuestionStatusClass(n-1)]"
            >
              {{ n }}
            </button>
          </div>

          <div class="space-y-3 mb-8 pt-6 border-t border-gray-100">
            <div class="flex items-center gap-3 text-[12px] text-gray-500 font-medium"><span class="w-3 h-3 rounded-full border-2 border-indigo-500"></span> Current</div>
            <div class="flex items-center gap-3 text-[12px] text-gray-500 font-medium"><span class="w-3 h-3 rounded-full bg-green-400"></span> Answered</div>
            <!-- <div class="flex items-center gap-3 text-[12px] text-gray-500 font-medium"><span class="w-3 h-3 rounded-full bg-yellow-400"></span> Marked</div> -->
            <div class="flex items-center gap-3 text-[12px] text-gray-500 font-medium"><span class="w-3 h-3 rounded-full bg-gray-100"></span> Not Answered</div>
          </div>

          <button @click="submitExam(false)" class="w-full bg-red-50 text-red-500 py-4 rounded-xl font-bold border border-red-100 hover:bg-red-100 transition-colors">
            Submit Exam
          </button>
        </div>
      </div>
    </div>

    <div v-else class="flex flex-col items-center justify-center min-h-[400px]">
      <p class="text-xl text-gray-500">No questions found for this exam.</p>
      <button @click="$router.back()" class="mt-4 text-indigo-600 font-bold underline">Go Back</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const router = useRouter();
const examId = route.params.id;

const studentId = localStorage.getItem('student_id') || 'guest'; 

// Storage Keys
const TIME_KEY = `exam_time_${studentId}_${examId}`;
const ANSWERS_KEY = `exam_answers_${studentId}_${examId}`;

const timeLeft = ref(0);
let timerInterval = null;

const questions = ref([]);
const totalQuestions = ref(0);
const currentQuestionIndex = ref(0);
const selectedOptions = ref([]);
const markedForReview = ref(new Set());
const loading = ref(true);

const formattedTime = computed(() => {
  const minutes = Math.floor(timeLeft.value / 60);
  const seconds = timeLeft.value % 60;
  return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
});

const startTimer = () => {
  if (timerInterval) clearInterval(timerInterval);
  timerInterval = setInterval(() => {
    if (timeLeft.value > 0) {
      timeLeft.value--;
      localStorage.setItem(TIME_KEY, timeLeft.value);
    } else {
      clearInterval(timerInterval);
      autoSubmitExam();
    }
  }, 1000);
};

const autoSubmitExam = () => {
  submitExam(true);
};

const fetchQuestions = async () => {
  try {
    loading.value = true;
    const token = localStorage.getItem('token');
    const response = await axios.get(`http://127.0.0.1:8000/api/student/exams/${examId}/questions`, {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    
    questions.value = response.data.data || response.data;
    totalQuestions.value = questions.value.length;
    
    // 1. Handle Time Persistence
    const savedTime = localStorage.getItem(TIME_KEY);
    if (savedTime && parseInt(savedTime) > 0) {
      timeLeft.value = parseInt(savedTime);
    } else {
      const examDuration = response.data.exam_duration || 30; 
      timeLeft.value = examDuration * 60;
      localStorage.setItem(TIME_KEY, timeLeft.value);
    }

    // 2. Handle Answers Persistence
    const savedAnswers = localStorage.getItem(ANSWERS_KEY);
    if (savedAnswers) {
      selectedOptions.value = JSON.parse(savedAnswers);
    } else {
      selectedOptions.value = new Array(totalQuestions.value).fill(null);
    }
    
    startTimer();
  } catch (error) {
    console.error("Error fetching questions:", error);
  } finally {
    loading.value = false;
  }
};

const selectOption = (index) => {
  selectedOptions.value[currentQuestionIndex.value] = index;
  // Save answer to localStorage on every click
  localStorage.setItem(ANSWERS_KEY, JSON.stringify(selectedOptions.value));
};

const clearExamStorage = () => {
  localStorage.removeItem(TIME_KEY);
  localStorage.removeItem(ANSWERS_KEY);
};

const submitExam = async (isAuto = false) => {
  if (!isAuto && !confirm("Are you sure you want to submit the exam?")) return;
  
  window.onbeforeunload = null;
  if (timerInterval) clearInterval(timerInterval);

  const payload = {
    exam_id: examId,
    answers: questions.value.map((q, index) => ({
      question_id: q.id,
      selected_option_id: selectedOptions.value[index] !== null ? q.options[selectedOptions.value[index]].id : null
    }))
  };

  try {
    const token = localStorage.getItem('token');
    await axios.post('http://127.0.0.1:8000/api/student/exam/submit', payload, {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    
    clearExamStorage(); // Clear storage on success
    alert(isAuto ? 'Time Expired! Submitted automatically.' : 'Exam Submitted Successfully!');
    router.push('/student/dashboard');
  } catch (error) {
    console.error("Submit Error:", error);
    alert('Error submitting exam.');
  }
};

// const toggleMarkForReview = () => {
//   if (markedForReview.value.has(currentQuestionIndex.value)) {
//     markedForReview.value.delete(currentQuestionIndex.value);
//   } else {
//     markedForReview.value.add(currentQuestionIndex.value);
//   }
// };

const nextQuestion = () => {
  if (currentQuestionIndex.value < totalQuestions.value - 1) {
    currentQuestionIndex.value++;
  } else {
    submitExam(false);
  }
};

const prevQuestion = () => {
  if (currentQuestionIndex.value > 0) currentQuestionIndex.value--;
};

const goToQuestion = (index) => {
  currentQuestionIndex.value = index;
};

const getQuestionStatusClass = (index) => {
  if (currentQuestionIndex.value === index) return 'border-indigo-500 text-indigo-600 ring-2 ring-indigo-100 ring-offset-1';
  if (markedForReview.value.has(index)) return 'bg-yellow-400 border-yellow-400 text-white';
  if (selectedOptions.value[index] !== null) return 'bg-green-400 border-green-400 text-white';
  return 'bg-gray-50 border-gray-200 text-gray-400';
};

onMounted(() => {
  fetchQuestions();
  window.onbeforeunload = (e) => {
    if (timeLeft.value > 0) {
      e.preventDefault();
      e.returnValue = '';
    }
  };
});

onUnmounted(() => {
  if (timerInterval) clearInterval(timerInterval);
});
</script>