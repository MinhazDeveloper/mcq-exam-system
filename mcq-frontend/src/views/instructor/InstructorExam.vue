<template>
  <div class="p-8 bg-gray-50 min-h-screen relative">
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Exams</h1>
        <p class="text-gray-500">Manage your examination content and settings</p>
      </div>
      <button
        @click="openModalForCreate"
        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold flex items-center gap-2 transition-all shadow-md shadow-indigo-100"
      >
        <span class="text-xl leading-none">+</span> Create New Exam
      </button>
    </div>

    <div class="p-6 bg-gray-100">
      <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
              <tr>
                <th class="px-6 py-3 text-left">Exam Title</th>
                <th class="px-6 py-3 text-left">Questions</th>
                <th class="px-6 py-3 text-left">Status</th>
                <th class="px-6 py-3 text-left">Last Modified</th>
                <th class="px-6 py-3 text-right">Actions</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
              <tr v-for="exam in exams" :key="exam.id">
                <td class="px-6 py-4 font-medium text-gray-900">{{ exam.title }}</td>
                <td class="px-6 py-4">{{ exam.questions }}</td>
                <td class="px-6 py-4">
                  <span class="px-2 py-1 text-xs font-medium rounded-full" :class="statusClasses[exam.status]">
                    {{ exam.status }}
                  </span>
                </td>
                <td class="px-6 py-4 text-gray-500">{{ exam.lastModified }}</td>
                <td class="px-6 py-4 text-right space-x-2">
                  <button @click="openModalForEdit(exam)" class="text-indigo-600 hover:text-indigo-900 font-semibold">Edit</button>
                  <button @click="handleDelete(exam.id)" class="text-red-600 hover:text-red-900 font-semibold">Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <Transition name="fade">
      <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="isModalOpen = false"></div>
        <div class="bg-white text-slate-800 w-full max-w-lg rounded-2xl shadow-2xl z-10 overflow-hidden relative">
          <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h2 class="text-xl font-bold text-slate-800">{{ isEditMode ? 'Edit Exam' : 'Create New Exam' }}</h2>
            <button @click="isModalOpen = false" class="text-slate-400 hover:text-slate-600">✕</button>
          </div>

          <form @submit.prevent="handleSubmit" class="p-6 space-y-5">
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">Exam Title</label>
              <input v-model="form.title" type="text" class="w-full px-4 py-2.5 rounded-xl border border-slate-200" required />
            </div>
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-1.5">Description</label>
              <textarea v-model="form.description" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
              <input v-model="form.total_marks" type="number" placeholder="Total Marks" class="w-full px-4 py-2.5 rounded-xl border border-slate-200" required />
              <input v-model="form.pass_marks" type="number" placeholder="Pass Marks" class="w-full px-4 py-2.5 rounded-xl border border-slate-200" required />
            </div>
            <input v-model="form.duration_minutes" type="number" placeholder="Duration (Min)" class="w-full px-4 py-2.5 rounded-xl border border-slate-200" required />
            
            <div class="flex items-center gap-3">
              <input v-model="form.is_published" type="checkbox" id="is_published" class="w-5 h-5 text-indigo-600 rounded" />
              <label for="is_published" class="text-sm font-semibold text-slate-700">Publish Exam</label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
              <button type="button" @click="isModalOpen = false" class="px-5 py-2.5 text-slate-600">Cancel</button>
              <button type="submit" class="px-8 py-2.5 rounded-xl bg-indigo-600 text-white font-semibold">
                {{ isEditMode ? 'Update Exam' : 'Save Exam' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios';

const isModalOpen = ref(false)
const isEditMode = ref(false)
const currentExamId = ref(null)
const exams = ref([])

const statusClasses = {
  Published: 'bg-green-100 text-green-700',
  Unpublished: 'bg-red-100 text-gray-600',
}

const form = reactive({
  title: '',
  description: '',
  total_marks: null,
  pass_marks: null,
  duration_minutes: null,
  is_published: false,
})

const fetchExams = async () => {
  try {
    const token = localStorage.getItem('token');
    const response = await axios.get('http://127.0.0.1:8000/api/instructor/exams', {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    const apiData = Array.isArray(response.data) ? response.data : response.data.data;
    exams.value = apiData.map(exam => ({
      ...exam,
      status: exam.is_published ? 'Published' : 'Unpublished',
      lastModified: exam.updated_at ? exam.updated_at.split('T')[0] : 'N/A'
    }));
  } catch (error) { console.error(error); }
}

const openModalForCreate = () => {
  isEditMode.value = false;
  Object.assign(form, { title: '', description: '', total_marks: null, pass_marks: null, duration_minutes: null, is_published: false });
  isModalOpen.value = true;
}

const openModalForEdit = (exam) => {
  isEditMode.value = true;
  currentExamId.value = exam.id;
  
  Object.assign(form, {
    title: exam.title,
    description: exam.description,
    total_marks: exam.total_marks,
    pass_marks: exam.pass_marks,
    duration_minutes: exam.duration_minutes,
    is_published: !!exam.is_published
  });
  isModalOpen.value = true;
}

const handleSubmit = async () => {
  try {
    const token = localStorage.getItem('token');
    const url = isEditMode.value 
      ? `http://127.0.0.1:8000/api/instructor/exams/${currentExamId.value}`
      : 'http://127.0.0.1:8000/api/instructor/exams';
    
    const method = isEditMode.value ? 'put' : 'post';

    await axios[method](url, form, {
      headers: { 'Authorization': `Bearer ${token}` }
    });

    await fetchExams();
    isModalOpen.value = false;
  } catch (error) { alert('Operation failed!'); }
}

const handleDelete = async (id) => {
  if (!confirm('Are you sure you want to delete this exam?')) return;
  try {
    const token = localStorage.getItem('token');
    await axios.delete(`http://127.0.0.1:8000/api/instructor/exams/${id}`, {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    await fetchExams();
  } catch (error) { alert('Delete failed!'); }
}

onMounted(fetchExams);
</script>