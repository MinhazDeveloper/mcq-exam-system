<template>
  <div class="p-6 max-w-4xl mx-auto bg-white shadow-lg rounded-xl">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold text-gray-800">AI Question Generator</h2>
      <div class="flex gap-4 mb-6">
        <div class="flex-1">
          <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Select Target Exam</label>
          <select 
            v-model="selectedExamId" 
            class="w-full border border-gray-300 p-2 rounded-lg text-sm focus:border-indigo-500 outline-none bg-gray-50 text-gray-900"
          >
            <option value="" disabled>Choose an exam...</option>
            <option v-for="exam in exams" :key="exam.id" :value="exam.id">{{ exam.title }}</option>
          </select>
        </div>

        <div class="w-32">
          <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Ques. Count</label>
          <select 
            v-model="questionCount" 
            class="w-full border border-gray-300 p-2 rounded-lg text-sm focus:border-indigo-500 outline-none bg-gray-50 text-gray-900"
          >
            <option :value="10">10 Questions</option>
            <option :value="20">20 Questions</option>
            <option :value="30">30 Questions</option>
            <option :value="40">40 Questions</option>
            <option :value="50">50 Questions</option>
          </select>
        </div>
      </div>

    </div>
    
    <div class="mb-6 border-2 border-dashed border-blue-300 p-8 text-center rounded-lg bg-blue-50">
      <input type="file" @change="handleFileUpload" class="hidden" ref="fileInput" accept=".jpg,.jpeg,.png,.pdf" />
      <div v-if="!loading">
        <p class="text-gray-600 mb-2">Upload image or PDF to generate questions</p>
        <button 
          @click="$refs.fileInput.click()" 
          :disabled="!selectedExamId"
          :class="[!selectedExamId ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700']"
          class="text-white px-6 py-2 rounded-full transition shadow-md"
        >
          {{ !selectedExamId ? 'Select Exam First' : 'Select File' }}
        </button>
      </div>
      
      <div v-else class="flex flex-col items-center">
        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
        <p class="mt-3 text-blue-600 font-medium tracking-wide">Intellecta AI is reading your file...</p>
      </div>
    </div>

    <div v-if="questions && questions.length > 0" class="space-y-6 animate-in fade-in duration-500">
      <h3 class="text-xl font-semibold border-b pb-2 flex items-center gap-2">
        <span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded text-sm">{{ questions.length }}</span>
        Generated Questions (Review)
      </h3>
      
      <div v-for="(q, index) in questions" :key="index" class="p-4 border rounded-lg bg-gray-50 relative border-l-4 border-l-indigo-500">
        <span class="absolute top-2 right-4 text-xs font-bold text-indigo-400">Question #{{ index + 1 }}</span>
        
        <div class="mb-3">
          <label class="block text-sm font-medium text-gray-700">Question Text:</label>
          <input 
            v-model="q.question" 
            class="w-full border border-gray-300 p-2 rounded mt-1 text-gray-900 bg-white focus:ring-2 focus:ring-indigo-200 outline-none" 
          />
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div v-for="(opt, optIndex) in q.options" :key="optIndex">
            <label class="text-xs text-gray-500 font-medium">Option {{ String.fromCharCode(65 + optIndex) }}:</label>
            <input 
              v-model="q.options[optIndex]" 
              class="w-full border border-gray-300 p-2 rounded text-sm text-gray-900 bg-white focus:ring-2 focus:ring-indigo-100 outline-none" 
            />
          </div>
        </div>

        <div class="mt-4 p-3 bg-green-50 rounded-lg border border-green-100">
          <label class="block text-xs font-bold text-green-700 uppercase mb-1">Correct Answer</label>
          <select 
            v-model="q.correct_answer" 
            class="w-full border border-green-300 p-2 rounded bg-white text-gray-900 focus:ring-2 focus:ring-green-200 outline-none font-medium"
          >
            <option v-for="(opt, oIdx) in q.options" :key="oIdx" :value="opt">
              {{ opt }}
            </option>
          </select>
        </div>
      </div>

      <button 
        @click="saveQuestions" 
        :disabled="saving"
        class="w-full bg-indigo-600 text-white py-4 rounded-xl font-bold hover:bg-indigo-700 shadow-xl transition-all flex justify-center items-center gap-2"
      >
        <span v-if="saving" class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></span>
        {{ saving ? 'Saving to Database...' : 'Finalize & Save All Questions' }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import api from "@/services/api";
import Swal from 'sweetalert2'; // importing SweetAlert2 for better alerts

const fileInput = ref(null);
const loading = ref(false);
const questions = ref([]);
const questionCount = ref(10); // default 10 questions 
const saving = ref(false);
const exams = ref([]);
const selectedExamId = ref("");

// Computed property to check if questions are valid for saving
const isReadyToSave = computed(() => {
  return questions.value.length > 0 && selectedExamId.value && !saving.value;
});

// Fetch Instructor's exams on mount
onMounted(async () => {
  try {
    const response = await api.get('/instructor/exams');
    exams.value = response.data.data || response.data;
  } catch (error) {
    console.error("Exams fetch error:", error);
  }
});

const handleFileUpload = async (event) => {
  const file = event.target.files[0];
  if (!file) return;

  // Client-side Validation
  const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
  if (!allowedTypes.includes(file.type)) {
    Swal.fire({
      icon: 'error',
      title: 'Invalid File',
      text: 'Please upload JPG, PNG or PDF files only.',
      confirmButtonColor: '#4F46E5'
    });
    return;
  }
  if (file.size > 5 * 1024 * 1024) { 
    Swal.fire('File too large', 'Max limit is 5MB.', 'warning');
    return;
  }

  const formData = new FormData();
  formData.append('file', file);
  formData.append('count', questionCount.value); 

  loading.value = true;
  questions.value = [];

  try {
    const response = await api.post('/instructor/questions/generate-from-ai', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    
    if (response.data.status === 'success') {
      questions.value = response.data.data;

      Swal.fire({
        icon: 'success',
        title: 'AI Generation Complete!',
        text: `Successfully generated ${questions.value.length} questions.`,
        toast: true,
        position: 'top-end',
        timer: 3000,
        showConfirmButton: false
      });  
    }
  } catch (error) {
    Swal.fire('AI Generation Failed', 'Please ensure the content is readable and try again.', 'error');
  } finally {
    loading.value = false;
    if (fileInput.value) fileInput.value.value = ''; // Reset file input

  }
};

const saveQuestions = async () => {
  if (!isReadyToSave.value) return;

  const confirmResult = await Swal.fire({
    title: 'Finalize Questions?',
    text: "Are you sure you want to save these to the question bank?",
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Yes, Save All',
    cancelButtonColor: '#9CA3AF',
    confirmButtonColor: '#4F46E5',
  });

  if (!confirmResult.isConfirmed) return;

  saving.value = true;

  try {
    const payload = {
      exam_id: selectedExamId.value,
      questions: questions.value.map(q => ({
        question: q.question,
        options: q.options, // ['Paris', 'London', ...]
        correct_answer: q.correct_answer // 'Paris'
      }))
    };

    // Call the bulk store API to save all questions at once
    const response = await api.post('/instructor/questions/bulk-store', payload);

    if (response.data.status === 'success') {
      await Swal.fire({
        icon: 'success',
        title: 'Mission Accomplished!',
        text: response.data.message || "Questions have been successfully added.",
        confirmButtonColor: '#4F46E5'
      });   
      questions.value = []; // Clear questions after successful save
      selectedExamId.value = ""; // Reset after success
    }
  } catch (error) {
    const errorMsg = error.response?.data?.error || "Failed to save questions.";
    Swal.fire('Error', errorMsg, 'error');
  } finally {
    saving.value = false;

  }
};
</script>