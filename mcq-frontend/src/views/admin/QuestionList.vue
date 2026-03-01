<template>
  <div class="p-6 bg-white rounded shadow">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-bold">All Questions</h2>
      <router-link to="/admin/questionform" class="bg-blue-600 text-white px-4 py-2 rounded shadow">
        + Add New Question
      </router-link>
    </div>

    <div class="space-y-6">
      <div v-for="(q, index) in questions" :key="q.id" class="p-5 border rounded-lg bg-gray-50 shadow-sm relative">
        
        <div class="flex justify-between items-start">
          <div class="flex-1">
            <h3 class="text-lg font-bold text-gray-800">
              {{ index + 1 }}. {{ q.question_text }}
            </h3>

            <div class="mt-3 ml-6 grid grid-cols-1 md:grid-cols-2 gap-2">
              <div v-for="(opt, optIndex) in q.options" :key="opt.id" class="text-gray-700">
                <span class="font-semibold">{{ String.fromCharCode(97 + optIndex) }})</span> 
                {{ opt.option_text }}
                
                <span v-if="opt.is_correct" class="ml-2 text-green-600 text-xs font-bold font-mono">
                  ✓
                </span>
              </div>
            </div>
          </div>

          <div class="flex flex-col space-y-2 ml-4">
            <button
              @click="openEditModal(q)"
              class="text-sm bg-blue-50 text-blue-600 px-3 py-1 rounded border border-blue-200 hover:bg-blue-600 hover:text-white transition"
            >
              Edit
            </button>

            <button
              @click="deleteQuestion(q.id)"
              class="text-sm bg-red-50 text-red-600 px-3 py-1 rounded border border-red-200 hover:bg-red-600 hover:text-white transition"
            >
              Delete
            </button>
          </div>

        </div>
      </div>
    </div>

    <!-- 🆕 Modal -->
    <div
      v-if="isModalOpen"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
    >
      <div
        class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6 shadow-xl"
      >
        <!-- Modal Header -->
        <div class="flex justify-between items-center mb-4 border-b pb-2">
          <h3 class="text-xl font-bold">Edit Question</h3>
          <button
            @click="closeModal"
            class="text-gray-500 hover:text-black text-2xl"
          >
            ×
          </button>
        </div>

        <!-- Modal Body -->
        <div class="space-y-4">
          <!-- 🔄 UPDATED: Question Text -->
          <div>
            <label class="block font-medium">Question Text</label>
            <textarea
              v-model="editForm.question_text"
              class="w-full border p-2 rounded h-24"
            ></textarea>
          </div>

          <!-- 🔄 UPDATED: Options Edit -->
          <div
            v-for="(opt, idx) in editForm.options"
            :key="idx"
            class="flex items-center gap-3"
          >
            <input
              type="checkbox"
              v-model="opt.is_correct"
              class="h-5 w-5"
            />
            <input
              type="text"
              v-model="opt.option_text"
              class="flex-1 border p-2 rounded"
              :placeholder="'Option ' + (idx + 1)"
            />
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="mt-6 flex justify-end gap-3">
          <button
            @click="closeModal"
            class="bg-gray-300 px-4 py-2 rounded"
          >
            Cancel
          </button>
          <button
            @click="updateQuestion"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
          >
            Update Question
          </button>
        </div>
      </div>
    </div>

    <div v-if="questions.length === 0" class="text-center py-10 text-gray-500 italic">
      No questions available. Please add some questions.
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import apiClient from '@/api/axios';

const questions = ref([]);

//Modal open/close state
const isModalOpen = ref(false);

//Edit form data
const editForm = ref({
  id: null,
  question_text: '',
  options: []
})

//UPDATED: Fetch questions (same logic, reused)
const fetchQuestions = async () => {
  try {
    const res = await apiClient.get('/admin/question/list')
    questions.value = res.data
  } catch (error) {
    console.error('Fetch error', error)
  }
}
//Modal open function
const openEditModal = (question) => {
  editForm.value = JSON.parse(JSON.stringify(question))
  isModalOpen.value = true
}
//Modal close
const closeModal = () => {
  isModalOpen.value = false
}
//Update question from modal
const updateQuestion = async () => {
  try {
    await apiClient.put(
      `/admin/question/${editForm.value.id}`,
      editForm.value
    )
    alert('Updated successfully!')
    closeModal()
    fetchQuestions()
  } catch (error) {
    alert('Update failed!')
    console.error(error)
  }
}
//Delete question (same logic)
const deleteQuestion = async (id) => {
  if (confirm('Are you sure you want to delete this?')) {
    try {
      await apiClient.delete(`/admin/question/${id}`)
      questions.value = questions.value.filter(q => q.id !== id)
      alert('Deleted successfully')
    } catch (error) {
      alert('Delete failed')
    }
  }
}

onMounted(fetchQuestions);

</script>