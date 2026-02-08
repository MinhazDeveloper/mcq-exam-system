<template>
  <div class="flex h-screen bg-[#F8FAFC] font-sans text-slate-700">
    <main class="flex-1 overflow-y-auto">
      <div class="p-10">
        <div class="grid grid-cols-4 gap-6 mb-10">
          <!-- <div v-for="stat in stats" :key="stat.title" class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm"> -->
          <div v-for="stat in stats" class="bg-white p-7 rounded-[32px] border border-slate-50 shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
              <!-- <div :class="stat.bgColor" class="p-3 rounded-xl text-white flex items-center justify-center">
                <svg v-if="stat.icon === 'user'" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 1.2c-3.6 0-10.8 1.8-10.8 5.4v2.4H22.8v-2.4c0-3.6-7.2-5.4-10.8-5.4z"/>
                </svg>
                <svg v-else-if="stat.icon === 'book'" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M3 7a2 2 0 012-2h7v12H4a2 2 0 01-2-2V7z" />
                  <path d="M21 5a2 2 0 00-2-2h-7v12h7a2 2 0 002-2V5z" />
                </svg>
                <svg v-else-if="stat.icon === 'graduation'" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M12 14l9-5-9-5-9 5 9 5z" />
                  <path d="M12 14v7" />
                  <path d="M12 14l6.16-3.422" />
                </svg>
                <svg v-else-if="stat.icon === 'money'" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M3 7h18v10H3zM7 11a2 2 0 100 4 2 2 0 000-4z"/>
                </svg>
              </div> -->

              <div :class="stat.bgColor" class="p-3 rounded-xl text-white flex items-center justify-center">
  
                <!-- ✅ যদি icon string হয় (user/book/money), তাহলে SVG দেখাবে -->
                <svg v-if="stat.icon === 'user'" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 1.2c-3.6 0-10.8 1.8-10.8 5.4v2.4H22.8v-2.4c0-3.6-7.2-5.4-10.8-5.4z"/>
                </svg>

                <svg v-else-if="stat.icon === 'book'" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M3 7a2 2 0 012-2h7v12H4a2 2 0 01-2-2V7z" />
                  <path d="M21 5a2 2 0 00-2-2h-7v12h7a2 2 0 002-2V5z" />
                </svg>

                <svg v-else-if="stat.icon === 'money'" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M3 7h18v10H3zM7 11a2 2 0 100 4 2 2 0 000-4z"/>
                </svg>

                <!-- ✅ যদি icon string না হয়ে image হয় (Total Attempts), তাহলে <img> দেখাবে -->
                <img
                  v-else
                  :src="stat.icon"
                  class="w-6 h-6 object-contain"
                  alt="icon"
                />
              </div>

              <span :class="stat.trend.includes('+') ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-400'" class="text-xs font-bold px-2 py-1 rounded-full">
                {{ stat.trend }}
              </span>
            </div>
            <p class="text-sm text-slate-400 font-medium">{{ stat.title }}</p>
            <h2 class="text-2xl font-black mt-1">{{ stat.value }}</h2>
          </div>
        </div>

        <div class="grid grid-cols-3 gap-8 mb-10">
          <div class="col-span-2 bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="flex justify-between items-center mb-8">
              <h3 class="text-lg font-bold">Revenue Overview</h3>
              <select class="bg-slate-50 border-none rounded-lg text-sm font-medium py-1 px-3">
                <option>This Week</option>
              </select>
            </div>
            <div class="h-64 flex items-end justify-between gap-4 px-2">
              <div v-for="h in [40, 30, 55, 35, 85, 25, 45]" :key="h" :style="{height: h + '%'}" class="flex-1 bg-indigo-50 rounded-t-lg relative group transition-all hover:bg-indigo-100">
                 <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">${{ h * 100 }}</div>
              </div>
            </div>
            <div class="flex justify-between mt-4 px-2 text-slate-400 text-xs font-bold">
              <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span>
            </div>
          </div>

          <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm flex flex-col">
            <h3 class="text-lg font-bold mb-6">Today's Exams</h3>
            <div class="space-y-4 flex-1">
              <div v-for="exam in todayExams" :key="exam.id" class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl group cursor-pointer transition">
                <div class="p-3 bg-indigo-100 text-indigo-600 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 11h4v-2h-3V7h-2v6z"/></svg>
                </div>
                <div class="flex-1">
                  <div class="flex justify-between">
                    <h4 class="text-sm font-bold truncate w-24">{{ exam.name }}</h4>
                    <span :class="exam.status === 'Ongoing' ? 'bg-green-100 text-green-600' : 'bg-orange-100 text-orange-500'" class="text-[10px] font-black px-2 py-0.5 rounded-md uppercase">
                      {{ exam.status }}
                    </span>
                  </div>
                  <p class="text-[11px] text-slate-400 mt-0.5">{{ exam.time }} • {{ exam.students }} Students</p>
                </div>
              </div>
            </div>
            <button class="mt-8 text-indigo-600 text-sm font-bold hover:underline">View All Exams</button>
          </div>
        </div> <div class="bg-white p-8 rounded-[32px] shadow-sm border border-slate-100">
          <div class="flex justify-between items-center mb-8">
            <h3 class="text-lg font-bold">Recent Activity</h3>
            <span class="text-slate-400 cursor-pointer text-xl">⋮</span>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full min-w-[800px] text-left border-collapse">
              <thead>
                <tr class="text-slate-400 text-[11px] uppercase tracking-widest border-b border-slate-50">
                  <th class="pb-5 font-semibold">User</th>
                  <th class="pb-5 font-semibold">Action</th>
                  <th class="pb-5 font-semibold">IP Address</th>
                  <th class="pb-5 font-semibold">Time</th>
                  <th class="pb-5 font-semibold text-right">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-50">
                <tr v-for="activity in activities" :key="activity.time" class="group hover:bg-slate-50 transition-colors">
                  <td class="py-5 font-bold text-slate-700">{{ activity.user }}</td>
                  <td class="py-5 text-slate-600">{{ activity.action }}</td>
                  <td class="py-5 text-slate-400 font-mono text-xs">{{ activity.ip }}</td>
                  <td class="py-5 text-slate-400 text-xs italic">{{ activity.time }}</td>
                  <td class="py-5 text-right">
                    <span :class="statusBadgeClass(activity.status)" class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider">
                      {{ activity.status }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        
      </div>
    </main>
  </div>
</template>

<script setup>

import graduationIcon from '@/assets/icons/total_attempts_icon.JPG'

// (স্ক্রিপ্ট সেকশন আগের মতোই থাকবে)
const stats = [
  { title: 'Total Students', value: '372', trend: '+12%', icon: 'user', bgColor: 'bg-blue-500' },
  { title: 'Total Exams', value: '40', trend: '+5%', icon: 'book', bgColor: 'bg-indigo-500' },
  // { title: 'Total Attempts', value: '1,284', trend: '-2%', icon: 'graduation', bgColor: 'bg-purple-500' },
    { title: 'Total Attempts', value: '1,284', trend: '-2%', icon: graduationIcon, bgColor: 'bg-purple-500' },

  { title: 'Total Revenue', value: '$48,290', trend: '+8%', icon: 'money', bgColor: 'bg-emerald-500' },
];

const todayExams = [
  { id: 1, name: 'Introduction to Compute...', time: '10:00 AM', students: 45, status: 'Ongoing' },
  { id: 2, name: 'Advanced Calculus II', time: '02:00 PM', students: 30, status: 'Upcoming' },
  { id: 3, name: 'Physics 101', time: '11:00 AM', students: 50, status: 'Upcoming' },
];

const activities = [
  { user: 'Alice Johnson', action: 'User Login', ip: '192.168.1.101', time: '2 mins ago', status: 'Info' },
  { user: 'Bob Smith', action: 'Exam Submission', ip: '192.168.1.102', time: '15 mins ago', status: 'Success' },
  { user: 'Unknown', action: 'Failed Login Attempt', ip: '45.33.22.11', time: '1 hour ago', status: 'Error' },
  { user: 'Dr. Sarah Lee', action: 'Profile Updated', ip: '192.168.1.50', time: '3 hours ago', status: 'Warning' },
  { user: 'Mike Chen', action: 'New Exam Created', ip: '192.168.1.51', time: '5 hours ago', status: 'Success' },
];

const statusBadgeClass = (status) => {
  const styles = {
    'Success': 'bg-emerald-50 text-emerald-600',
    'Error': 'bg-rose-50 text-rose-500',
    'Warning': 'bg-amber-50 text-amber-600',
    'Info': 'bg-sky-50 text-sky-600'
  };
  return styles[status] || 'bg-slate-100 text-slate-500';
};
</script>