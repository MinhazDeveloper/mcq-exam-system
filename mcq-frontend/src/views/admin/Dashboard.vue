<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        
        <!-- Left: Title -->
        <h1 class="text-2xl font-bold text-gray-800">
          Dashboard
        </h1>

        <!-- Right: Create Question Button -->
        <router-link
          to="/admin/questionform"
          class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md font-semibold transition"
        >
          Create Question
        </router-link>

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


