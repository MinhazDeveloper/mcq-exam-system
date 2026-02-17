<template>
  <div class="p-8 bg-gray-50 min-h-screen font-sans">
    <div class="mb-8">
      <h1 class="text-2xl font-extrabold text-[#111827]">Enrolled Exams</h1>
      <p class="text-gray-500 mt-1">Manage and attend your scheduled examinations.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      
      <div class="bg-white rounded-[24px] p-8 border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative">
        <div class="flex justify-between items-start mb-6">
          <span class="text-[12px] font-bold tracking-widest text-[#4F46E5] uppercase">MATHEMATICS</span>
          <span class="flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-500 rounded-full text-[11px] font-bold border border-red-100">
            <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span> Live
          </span>
        </div>
        <h2 class="text-xl font-bold text-gray-900 leading-tight mb-6">Advanced Calculus Midterm</h2>
        <div class="space-y-3 mb-8">
          <div class="flex items-center text-gray-500 text-sm gap-3">
            <span>🕒</span> 60 minutes
          </div>
          <div class="flex items-center text-gray-500 text-sm gap-3">
            <span>📋</span> 50 Questions
          </div>
          <div class="flex items-center text-gray-500 text-sm gap-3">
            <span>📅</span> 10/25/2023 • 09:00 AM
          </div>
        </div>
        <button class="w-full py-4 bg-[#4F46E5] text-white rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-[#4338CA] transition shadow-[0_10px_20px_-5px_rgba(79,70,229,0.4)]">
          Start Exam <span>→</span>
        </button>
      </div>

      <div class="bg-white rounded-[24px] p-8 border border-gray-100 shadow-sm opacity-90">
        <div class="flex justify-between items-start mb-6">
          <span class="text-[12px] font-bold tracking-widest text-[#4F46E5] uppercase">PHYSICS</span>
          <span class="flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-500 rounded-full text-[11px] font-bold border border-blue-100">
            <span>📅</span> Upcoming
          </span>
        </div>
        <h2 class="text-xl font-bold text-gray-900 leading-tight mb-6">Physics 101:<br>Mechanics</h2>
        <div class="space-y-3 mb-8">
          <div class="flex items-center text-gray-500 text-sm gap-3">
            <span>🕒</span> 45 minutes
          </div>
          <div class="flex items-center text-gray-500 text-sm gap-3">
            <span>📋</span> 40 Questions
          </div>
          <div class="flex items-center text-gray-500 text-sm gap-3">
            <span>📅</span> 10/26/2023 • 02:00 PM
          </div>
        </div>
        <button disabled class="w-full py-4 bg-gray-50 text-gray-400 rounded-xl font-bold cursor-not-allowed border border-gray-100">
          Wait to Start
        </button>
      </div>

      <div class="bg-white rounded-[24px] p-8 border border-gray-100 shadow-sm">
        <div class="flex justify-between items-start mb-6">
          <span class="text-[12px] font-bold tracking-widest text-[#4F46E5] uppercase">COMPUTER SCI...</span>
          <span class="flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-500 rounded-full text-[11px] font-bold border border-green-100">
            <span>✅</span> Completed
          </span>
        </div>
        <h2 class="text-xl font-bold text-gray-900 leading-tight mb-6">Introduction to Computer Science</h2>
        <div class="space-y-3 mb-8">
          <div class="flex items-center text-gray-500 text-sm gap-3">
            <span>🕒</span> 90 minutes
          </div>
          <div class="flex items-center text-gray-500 text-sm gap-3">
            <span>📋</span> 60 Questions
          </div>
          <div class="flex items-center text-gray-500 text-sm gap-3">
            <span>📅</span> 10/20/2023 • 10:00 AM
          </div>
        </div>
        <button class="w-full py-4 bg-gray-50 text-gray-600 rounded-xl font-bold hover:bg-gray-100 transition border border-gray-100">
          View Results
        </button>
      </div>

    </div>
  </div>
</template>