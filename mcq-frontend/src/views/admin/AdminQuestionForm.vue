<template>
  <div class="p-6 bg-white rounded shadow">
    <!-- <h2 class="text-xl font-bold mb-4">Create New MCQ</h2> -->
    <!-- ✅ Title dynamic -->
    <h2 class="text-xl font-bold mb-4">
      {{ isEdit ? 'Edit Question' : 'Create New MCQ' }}
    </h2>

    <!-- ✅ Select Exam -->
    <!-- <div class="mb-4">
      <label class="block mb-1 font-medium">Select Exam</label>
      <select
        v-model="form.exam_id"
        class="w-64 border p-2 rounded"
        required
      >
        <option value="">-- Choose an Exam --</option>
        <option
          v-for="exam in exams"
          :key="exam.id"
          :value="exam.id"
        >
          {{ exam.title }}
        </option>
      </select>
    </div> -->
    

    <!-- ✅ Select Exam -->
    <div class="mb-4">
      <label class="block mb-1 font-medium">Select Exam</label>
      <select
        v-model="form.exam_id"
        class="w-64 border p-2 rounded"
        required
      >
        <option value="">-- Choose an Exam --</option>
        <option
          v-for="exam in exams"
          :key="exam.id"
          :value="exam.id"
        >
          {{ exam.title }}
        </option>
      </select>
    </div>

    <div class="mb-4">
      <label class="block mb-1 font-medium">Question Text</label>
      <input
        v-model="form.question_text"
        class="w-full max-w-md h-9 border px-2 rounded"
        placeholder="Enter question"
      />
    </div>
    <!-- <div
      v-for="(option, index) in form.options"
      :key="index"
      
      class="flex items-center gap-2 mb-2"
    > -->
    <div
      v-for="(option, index) in form.options"
      :key="option.id ?? index"

      class="flex items-center gap-2 mb-2"

    >
      <!-- Checkbox -->
      <input
        type="checkbox"
        v-model="option.is_correct"
        class="h-4 w-4"
      >

      <!-- Option Text -->
      <input
        v-model="option.option_text"
        class="w-full max-w-sm h-8 border px-2 rounded text-sm"
        :placeholder="'Option ' + (index + 1)"
      >

      <!-- Remove Button -->
      <button
        @click="removeOption(index)"
        class="text-red-500 text-sm font-bold"
      >
        ✕
      </button>
    </div>

    <button @click="addOption" class="bg-blue-500 text-white px-4 py-2 rounded">+ Add Option</button>
<!-- ✅ Button dynamic -->
    <button
      @click="submitQuestion"
      class="bg-green-600 text-white px-4 py-2 rounded ml-2"
    >
      {{ isEdit ? 'Update Question' : 'Save Question' }}
    </button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import apiClient from '@/api/axios';

const route = useRoute();
const router = useRouter();
const isEdit = ref(false);

const exams = ref([]);

const form = ref({
  exam_id: '',
  question_text: '',
  mark: 1,
  options: [
    // { option_text: '', is_correct: false },
    // { option_text: '', is_correct: false }

    { id: null, option_text: '', is_correct: false },
    { id: null, option_text: '', is_correct: false }

  ]
});

// fetch exams from backend
onMounted(async () => {
  try {
    const res = await apiClient.get('/admin/exams')
    exams.value = res.data

    //Edit mode check
    if (route.params.id) {
      isEdit.value = true;

      const qRes = await apiClient.get(`/admin/question/${route.params.id}`);

      // ✅ Proper mapping (important)
      form.value.exam_id = qRes.data.exam_id;
      form.value.question_text = qRes.data.question_text;
      form.value.mark = qRes.data.mark ?? 1;
      // form.value.options = qRes.data.options;
      form.value.options = qRes.data.options.map(opt => ({
        id: opt.id ?? null,
        option_text: opt.option_text,
        is_correct: opt.is_correct
      }));
    }
  } catch (error) {
    console.error('Exam load failed', error)
  }
})

const addOption = () => form.value.options.push({ id: null, option_text: '', is_correct: false });
// const removeOption = (index) => form.value.options.splice(index, 1);
const removeOption = (index) => {
  if (form.value.options.length > 2) {
    form.value.options.splice(index, 1);
  }
};

const submitQuestion = async () => {
  try {
     if (isEdit.value) {
      await apiClient.put(
        `/admin/question/${route.params.id}`,
        form.value
      );
    } else {
      await apiClient.post('/admin/question', form.value);
    }

    alert('Success!');
    router.push('/admin/question/list');

  } catch (err) {
    alert(err.response.data.message || 'Validation Error');
  }
  
};

</script>