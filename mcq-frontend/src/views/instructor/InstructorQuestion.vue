<template>
  <div class="p-8 bg-[#F9FAFB] min-h-screen">
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-2xl font-bold text-[#111827]">Question Bank</h1>
        <p class="text-sm text-gray-500 mt-1">Centralized repository for all your exam questions</p>
      </div>
      <div class="flex gap-3">
        <!-- <button class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
          <span>📄</span> Import CSV
        </button> -->
        <button 
          @click="resetForm(); showModal = true" 
          class="flex items-center gap-2 px-4 py-2 bg-[#4F46E5] text-white rounded-lg text-sm font-semibold hover:bg-[#4338CA] transition shadow-sm"
        >
          <span class="text-lg">+</span> Add Question
        </button>
      </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <table class="w-full text-left">
        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
          <tr class="text-[11px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-50">
            <th class="px-8 py-4">Question</th>
            <th class="px-6 py-4">Exam</th>
            <th class="px-6 py-4 text-center">Difficulty</th>
            <th class="px-6 py-4">Type</th>
            <th class="px-8 py-4 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-if="loading">
            <td colspan="5" class="px-8 py-10 text-center text-gray-400 italic">Loading questions...</td>
          </tr>
          <tr v-else-if="filteredQuestions.length === 0">
            <td colspan="5" class="px-8 py-10 text-center text-gray-500 italic">No questions found.</td>
          </tr>
          <tr v-for="q in filteredQuestions" :key="q.id" class="hover:bg-gray-50/50 transition">
            <td class="px-8 py-5 max-w-[300px]">
              <div class="font-medium text-gray-900 truncate" :title="q.question_text">
                {{ q.question_text }}
              </div>
            </td>
            <td class="px-6 py-5 text-sm text-gray-600">{{ q.exam.title }}</td>
            <td class="px-6 py-5 text-center">
              <span :class="difficultyBadge(q.difficulty)">
                {{ q.difficulty }}
              </span>
            </td>
            <td class="px-6 py-5 text-sm text-gray-600">{{ q.type }}</td>
            <td class="px-6 py-4 text-right space-x-2">
              <button @click="editQuestion(q)" class="text-indigo-600 hover:text-indigo-900 font-semibold">Edit</button>
              <button @click="deleteQuestion(q.id)" class="text-red-600 hover:text-red-900 font-semibold">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <Teleport to="body">
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" @click.self="showModal = false">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl border border-gray-100 flex flex-col max-h-[90vh] overflow-hidden animate-in fade-in zoom-in duration-200">
          <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">{{ isEditing ? 'Edit Question' : 'Add New Question' }}</h3>
            <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition">✕</button>
          </div>

          <div class="p-6 space-y-6 overflow-y-auto custom-scrollbar">
            <div>
              <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Question Text</label>
              <textarea v-model="form.question_text" placeholder="Enter your question here..." class="w-full p-3 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-indigo-500 outline-none h-24 resize-none text-sm bg-gray-50/30"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Exam</label>
                <select v-model="form.exam_id" class="w-full p-2.5 border border-gray-200 rounded-xl text-gray-900 bg-white focus:ring-2 focus:ring-indigo-500 text-sm outline-none">
                  <option disabled value="null">Select Exam</option>
                  <option v-for="exam in exams" :key="exam.id" :value="exam.id">{{ exam.title }}</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Difficulty</label>
                <select v-model="form.difficulty" class="w-full p-2.5 border border-gray-200 rounded-xl text-gray-900 bg-white focus:ring-2 focus:ring-indigo-500 text-sm outline-none">
                  <option>Easy</option>
                  <option>Medium</option>
                  <option>Hard</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-gray-700 uppercase mb-3">Answer Options (Select the correct one)</label>
              <div class="space-y-3">
                <div v-for="(option, index) in form.options" :key="index" class="flex items-start gap-3 group">
                  <div class="pt-3">
                    <input type="radio" :name="'correct_option'" v-model="selectedCorrectIndex" :value="index" class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 cursor-pointer" />
                  </div>
                  <input v-model="option.text" :placeholder="'Option ' + (index + 1)" class="flex-1 p-2.5 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-indigo-500 outline-none text-sm" />
                  <button v-if="form.options.length > 2" @click="removeOption(index)" class="pt-2 text-gray-300 hover:text-red-500 transition">🗑️</button>
                </div>
              </div>
              <button @click="addOption" class="mt-4 text-indigo-600 text-sm font-semibold flex items-center gap-1 hover:underline">
                <span>+</span> Add Option
              </button>
            </div>
          </div>

          <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 flex justify-end items-center gap-4">
            <button @click="showModal = false" class="text-sm font-semibold text-gray-500 hover:text-gray-700 transition">Cancel</button>
            <button @click="saveQuestion" class="px-6 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg text-sm font-bold shadow-md transition">Save Question</button>
          </div>
          
        </div>
      </div>  
    </Teleport>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const exams = ref([]);
const questions = ref([]);
const showModal = ref(false);
const searchQuery = ref('');
const loading = ref(false);
const selectedCorrectIndex = ref(null);
const isEditing = ref(false);
const editingId = ref(null);

const form = ref({
  question_text: '',
  exam_id: null,
  difficulty: 'Medium',
  mark: 1,
  options: [
    { text: '', is_correct: false },
    { text: '', is_correct: false },
    { text: '', is_correct: false },
    { text: '', is_correct: false }
  ]
});
const fetchExams = async () => {
  try {
    const token = localStorage.getItem('token'); 

    const response = await axios.get('http://127.0.0.1:8000/api/instructor/exams', {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    });

    if (response.data?.success) exams.value = response.data.data;

  } catch (error) {
    console.error("Exams Fetch Error:", error.response?.data);
  }
}

const fetchQuestions = async () => {
  loading.value = true;
  try {
    const token = localStorage.getItem('token');
    const response = await axios.get('http://127.0.0.1:8000/api/instructor/questions', {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    if (response.data.success) {
      questions.value = response.data.data.data; 
    }
    
  } catch (error) {
    console.error("Error fetching questions:", error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchExams();
  fetchQuestions();
});

const editQuestion = (q) => {
  console.log("Full Question Object:", q);
  isEditing.value = true;
  editingId.value = q.id;
  
  form.value.question_text = q.question_text;
  form.value.exam_id = q.exam_id;
  form.value.difficulty = q.difficulty;
  form.value.mark = q.mark || 1;

  form.value.options = q.options.map((opt, index) => {
    if (Number(opt.is_correct) === 1 || opt.is_correct === true) {
        selectedCorrectIndex.value = index;
    }  
    return { 
      text: opt.option_text,
      is_correct: !!opt.is_correct 
    };
  });

  showModal.value = true;
};

const deleteQuestion = async (id) => {
  if (!confirm("Are you sure you want to delete this question?")) return;

  try {
    const token = localStorage.getItem('token');
    await axios.delete(`http://127.0.0.1:8000/api/instructor/questions/${id}`, {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    alert("Question deleted successfully.");
    await fetchQuestions();
  } catch (error) {
    console.error("Delete Error:", error);
  }
};

const resetForm = () => {
  isEditing.value = false;
  editingId.value = null;
  selectedCorrectIndex.value = null;
  form.value = { 
    question_text: '', 
    exam_id: null, 
    difficulty: 'Medium', 
    mark: 1,
    options: [
      { text: '', is_correct: false },
      { text: '', is_correct: false },
      { text: '', is_correct: false },
      { text: '', is_correct: false }
    ] 
  };
};
const filteredQuestions = computed(() => {
  return questions.value.filter(q => q.question_text.toLowerCase().includes(searchQuery.value.toLowerCase()));
});

// Styling Logic for Difficulty Badges
const difficultyBadge = (level) => {
  const base = "px-3 py-1 text-[10px] font-bold rounded-full border ";
  if (level === 'Easy') return base + "bg-green-50 text-green-500 border-green-100";
  if (level === 'Medium') return base + "bg-blue-50 text-blue-500 border-blue-100";
  if (level === 'Hard') return base + "bg-red-50 text-red-500 border-red-100";
  return base;
};

const addOption = () => form.value.options.push({ text: '', is_correct: false });
const removeOption = (index) => form.value.options.length > 2 && form.value.options.splice(index, 1);

const saveQuestion = async () => {
  try {
    if (!form.value.question_text || !form.value.exam_id) {
      alert("Please fill all required fields.");
      return;
    }

    if (selectedCorrectIndex.value === null) {
      alert("Please select the correct answer.");
      return;
    }

    const preparedOptions = form.value.options.map((opt, index) => ({
      text: opt.text,
      is_correct: index === selectedCorrectIndex.value
    }));

    const token = localStorage.getItem('token');
   
    const payload = {
      question_text: form.value.question_text,
      exam_id: form.value.exam_id,
      difficulty: form.value.difficulty,
      mark: form.value.mark,
      explanation: form.value.explanation || '',
      options: preparedOptions
    };
    
    if (isEditing.value) {
      payload._method = 'PUT';
    }

    const url = isEditing.value 
      ? `http://127.0.0.1:8000/api/instructor/questions/${editingId.value}`
      : 'http://127.0.0.1:8000/api/instructor/questions';


    const response = await axios({
      method: 'post',
      url: url,
      data: payload,
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    });

    if (response.status === 201 || response.status === 200) {
      alert(isEditing.value ? 'Updated successfully!' : 'Saved successfully!');
      showModal.value = false; 
      resetForm();             
      await fetchQuestions();  
    }

  } catch (error) {
    console.error("Error:", error.response?.data);
    const serverErrors = error.response?.data?.errors;
    alert(serverErrors ? Object.values(serverErrors).flat().join('\n') : "Something went wrong!");
  }
};
</script>