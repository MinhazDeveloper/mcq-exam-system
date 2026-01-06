<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        
        <!-- Left: Title -->
        <h1 class="text-2xl font-bold text-gray-800">
          Dashboard
        </h1>
        <div class="flex items-center gap-4">
          <select 
            v-model="selectedExamId" 
            class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
          >
            <option value="" disabled>Select an Exam</option>
            <option v-for="exam in exams" :key="exam.id" :value="exam.id">
              {{ exam.title }}
            </option>
          </select>
 
          <button
            type="button"
            @click="goToExam"
            :disabled="!selectedExamId"
            :class="[
              'px-4 py-2 rounded-md font-semibold transition',
              selectedExamId ? 'bg-indigo-600 hover:bg-indigo-700 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed'
            ]"
          >
            Start Exam
          </button>
        </div>
      </div>
    </header>
    
    <!-- Content -->
    <main class="max-w-7xl mx-auto px-6 py-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Card 1 -->
        <div class="bg-white p-6 rounded-lg shadow">
          <h2 class="text-lg font-semibold text-gray-700">Total Exams</h2>
          <p class="text-3xl font-bold text-indigo-600 mt-2">12</p>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-6 rounded-lg shadow">
          <h2 class="text-lg font-semibold text-gray-700">Total Students</h2>
          <p class="text-3xl font-bold text-green-600 mt-2">120</p>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-lg shadow">
          <h2 class="text-lg font-semibold text-gray-700">Submissions</h2>
          <p class="text-3xl font-bold text-orange-600 mt-2">85</p>
        </div>

      </div>
    </main>
  </div>
</template>

<script setup>

  import { ref, onMounted } from 'vue'
  import { useRouter } from 'vue-router'
  import apiClient from '@/api/axios';

  const router = useRouter()
  const exams = ref([])      
  const selectedExamId = ref('') 
  const loading = ref(true)

  onMounted(async () => {
    try {
      const res = await apiClient.get('/student/exams')
      exams.value = res.data 
    } catch (error) {
      console.error("Exams fetch failed", error)
    } finally {
      loading.value = false
    }
  })

  const goToExam = () => {
    if (selectedExamId.value) {
      router.push({ name: 'StudentExam', params: { id: selectedExamId.value } })
    }
  }

</script>
