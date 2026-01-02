<template>
  <div class="p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">Create New MCQ</h2>
    
    <div class="mb-4">
      <label>Question Text</label>
      <input v-model="form.question_text" class="w-full border p-2 rounded" placeholder="Enter question">
    </div>

    <div v-for="(option, index) in form.options" :key="index" class="flex items-center gap-2 mb-2">
      <input type="checkbox" v-model="option.is_correct">
      <input v-model="option.option_text" class="flex-1 border p-2 rounded" :placeholder="'Option ' + (index+1)">
      <button @click="removeOption(index)" class="text-red-500">X</button>
    </div>

    <button @click="addOption" class="bg-blue-500 text-white px-4 py-2 rounded">+ Add Option</button>
    <button @click="submitQuestion" class="bg-green-600 text-white px-4 py-2 rounded ml-2">Save Question</button>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const form = ref({
  question_text: '',
  mark: 1,
  options: [
    { option_text: '', is_correct: false },
    { option_text: '', is_correct: false }
  ]
});

const addOption = () => form.value.options.push({ option_text: '', is_correct: false });
const removeOption = (index) => form.value.options.splice(index, 1);

const submitQuestion = async () => {
  try {
    const res = await axios.post('/api/admin/questions', form.value);
    alert('Success!');
    // Reset form
  } catch (err) {
    alert(err.response.data.message || 'Validation Error');
  }
};
</script>