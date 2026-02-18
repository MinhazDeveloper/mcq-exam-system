<template>
  <div class="flex h-screen bg-[#F8FAFC] font-sans text-slate-700">
    <main class="flex-1 overflow-y-auto">
      <div class="p-10">
  <!--  <div class="grid grid-cols-4 gap-6 mb-10">
          <div v-for="stat in stats" :key="stat.title" class="bg-white p-7 rounded-[32px] border border-slate-50 shadow-sm relative overflow-hidden">
            
            <div v-if="loading" class="animate-pulse">
              <div class="flex justify-between mb-4">
                <div class="w-12 h-12 bg-slate-200 rounded-xl"></div>
                <div class="w-10 h-5 bg-slate-100 rounded-full"></div>
              </div>
              <div class="h-4 bg-slate-100 rounded w-1/2 mb-2"></div>
              <div class="h-8 bg-slate-200 rounded w-3/4"></div>
            </div>

            <div v-else>
              <div class="flex justify-between items-start mb-6">
                <div :class="stat.bgColor" class="w-12 h-12 rounded-[18px] text-white flex items-center justify-center">
                  <component :is="stat.icon" :size="24" stroke-width="2.2" />
                </div>
                 <span :class="stat.trend.includes('+') ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-400'" class="text-xs font-bold px-2.5 py-1 rounded-full">
                  {{ stat.trend }}
                </span>
              </div>
              <p class="text-[14px] text-slate-400 font-medium tracking-tight">{{ stat.title }}</p>
              <h2 class="text-2xl font-bold text-slate-900 mt-1">{{ stat.value }}</h2>
            </div>
          </div>
        </div>-->

        <div class="grid grid-cols-4 gap-6 mb-10">
          <div v-for="stat in stats" :key="stat.title" class="bg-white p-7 rounded-[32px] border border-slate-50 shadow-sm relative overflow-hidden">
            <div v-if="loading" class="animate-pulse">
              <div class="flex justify-between mb-4">
                <div class="w-12 h-12 bg-slate-200 rounded-xl"></div>
                <div class="w-10 h-5 bg-slate-100 rounded-full"></div>
              </div>
              <div class="h-4 bg-slate-100 rounded w-1/2 mb-2"></div>
              <div class="h-8 bg-slate-200 rounded w-3/4"></div>
            </div>

            <div v-else>
              <div class="flex justify-between items-start mb-6">
                <div :class="stat.bgColor" class="w-12 h-12 rounded-[18px] text-white flex items-center justify-center">
                  <component :is="stat.icon" :size="24" stroke-width="2.2" />
                </div>
              </div>
              <p class="text-[14px] text-slate-400 font-medium tracking-tight">{{ stat.title }}</p>
              <h2 class="text-2xl font-bold text-slate-900 mt-1">{{ stat.value }}</h2>
            </div>
          </div>
        </div>
        

        <!-- <div class="grid grid-cols-3 gap-8 mb-10">
          <div class="col-span-2 bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="flex justify-between items-center mb-8">
              <div>
                <h3 class="text-lg font-bold text-slate-800">Registration Trend</h3>
                <p class="text-xs text-slate-400 font-medium mt-1">New students in last 7 days</p>
              </div>
              <select class="bg-slate-50 border-none rounded-lg text-sm font-medium py-1.5 px-3 focus:ring-2 focus:ring-indigo-500">
                <option>This Week</option>
              </select>
            </div>

            <div class="h-64 flex items-end justify-between gap-4 px-2">
              <div v-for="item in registrationData" :key="item.day" 
                  class="flex-1 bg-slate-50 rounded-t-2xl relative group transition-all duration-300 hover:bg-slate-100">
                
                <div 
                  :style="{ height: item.count > 0 ? (item.count * 15) + 'px' : '4px' }" 
                  class="w-full bg-indigo-500 rounded-t-2xl transition-all duration-500 group-hover:bg-indigo-600 relative"
                >
                  <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[11px] font-bold px-2.5 py-1.5 rounded-xl opacity-0 group-hover:opacity-100 transition-all pointer-events-none whitespace-nowrap shadow-lg">
                    {{ item.count }} New Students
                  </div>
                </div>
              </div>
            </div>

            <div class="flex justify-between mt-6 px-2 text-slate-400 text-[11px] font-bold uppercase tracking-widest">
              <span v-for="item in registrationData" :key="item.day">{{ item.day }}</span>
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
                    <h4 class="text-sm font-bold truncate w-24">{{ exam.title }}</h4>
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
        </div> -->

        <div class="grid grid-cols-3 gap-8 mb-10">
          <div class="col-span-2 bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="flex justify-between items-center mb-8">
              <div>
                <h3 class="text-lg font-bold text-slate-800">Registration Trend</h3>
                <p class="text-xs text-slate-400 font-medium mt-1">New students in last 7 days</p>
              </div>
            </div>

            <div class="h-64 flex items-end justify-between gap-4 px-2">
              <div v-for="item in registrationData" :key="item.day" 
                  class="flex-1 bg-slate-50 rounded-t-2xl relative group transition-all duration-300 hover:bg-slate-100">
                <div 
                  :style="{ height: item.count > 0 ? (item.count * 15) + 'px' : '4px' }" 
                  class="w-full bg-indigo-500 rounded-t-2xl transition-all duration-500 group-hover:bg-indigo-600 relative"
                >
                  <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[11px] font-bold px-2.5 py-1.5 rounded-xl opacity-0 group-hover:opacity-100 transition-all pointer-events-none whitespace-nowrap shadow-lg">
                    {{ item.count }} New Students
                  </div>
                </div>
              </div>
            </div>

            <div class="flex justify-between mt-6 px-2 text-slate-400 text-[11px] font-bold uppercase tracking-widest">
              <span v-for="item in registrationData" :key="item.day">{{ item.day }}</span>
            </div>
          </div>

          <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm flex flex-col">
            <h3 class="text-lg font-bold mb-6">Today's Exams</h3>
            <div class="space-y-4 flex-1">
              <div v-if="todayExams.length === 0 && !loading" class="text-center py-10 text-slate-400 italic text-sm">
                No exams scheduled for today.
              </div>

              <div v-for="exam in todayExams" :key="exam.id" class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl group cursor-pointer transition">
                <div class="p-3 bg-indigo-100 text-indigo-600 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition">
                  <BookOpen :size="20" />
                </div>
                <div class="flex-1">
                  <div class="flex justify-between items-center">
                    <h4 class="text-sm font-bold truncate w-32 text-slate-700">{{ exam.title }}</h4>
                    <span class="bg-indigo-50 text-indigo-600 text-[10px] font-black px-2 py-0.5 rounded-md uppercase">
                      {{ exam.total_marks || 0 }} Marks
                    </span>
                  </div>
                  <!-- <p class="text-[11px] text-slate-400 mt-0.5">{{ exam.duration_minutes }}</p> -->
                </div>
              </div>
            </div>
            <button class="mt-8 text-indigo-600 text-sm font-bold hover:underline">View All Exams</button>
          </div>
        </div>

        <div class="bg-white p-8 rounded-[32px] shadow-sm border border-slate-100">
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
              <!-- <tbody class="divide-y divide-slate-50">
                <tr v-for="activity in activities" :key="activity.time" class="group hover:bg-slate-50 transition-colors">
                  <td class="py-5 font-bold text-slate-700">{{ activity.user_name }}</td>
                  <td class="py-5 text-slate-600">{{ activity.action }}</td>
                  <td class="py-5 text-slate-400 font-mono text-xs">{{ activity.ip_address }}</td>
                  <td class="py-5 text-slate-400 text-xs italic">{{ activity.created_at_human }}</td>
                  <td class="py-5 text-right">
                    <span :class="statusBadgeClass(activity.status)" class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider">
                      {{ activity.status }}
                    </span>
                  </td>
                </tr>
              </tbody> -->

              <tbody class="divide-y divide-slate-50">
                <tr v-if="activities.length === 0">
                  <td colspan="5" class="py-10 text-center text-slate-400 italic">
                    No recent activities found.
                  </td>
                </tr>

                <tr v-for="activity in activities" :key="activity.id" class="group hover:bg-slate-50 transition-colors">
                  <td class="py-5 font-bold text-slate-700">{{ activity.user_name || 'System' }}</td>
                  <td class="py-5 text-slate-600">{{ activity.action }}</td>
                  <td class="py-5 text-slate-400 font-mono text-xs">{{ activity.ip_address || 'N/A' }}</td>
                  <td class="py-5 text-slate-400 text-xs italic">{{ activity.created_at_human }}</td>
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
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Users, BookOpen, } from 'lucide-vue-next';

const loading = ref(true);
const registrationData = ref([]);
const todayExams = ref([]);
const activities = ref([]); 

const stats = ref([
  { title: 'Total Students', value: '0', trend: '0%', icon: Users, bgColor: 'bg-blue-500', key: 'total_students' },
  { title: 'Total Exams', value: '0', trend: '0%', icon: BookOpen, bgColor: 'bg-indigo-500', key: 'total_exams' },
  // { title: 'Total Attempts', value: '0', trend: '0%', icon: GraduationCap, bgColor: 'bg-purple-500', key: 'total_attempts' },
  // { title: 'Total Revenue', value: '$0', trend: '0%', icon: DollarSign, bgColor: 'bg-emerald-500', key: 'total_revenue' },
]);

// const activities = [
//   { user: 'Alice Johnson', action: 'User Login', ip: '192.168.1.101', time: '2 mins ago', status: 'Info' },
//   { user: 'Bob Smith', action: 'Exam Submission', ip: '192.168.1.102', time: '15 mins ago', status: 'Success' },
//   { user: 'Unknown', action: 'Failed Login Attempt', ip: '45.33.22.11', time: '1 hour ago', status: 'Error' },
//   { user: 'Dr. Sarah Lee', action: 'Profile Updated', ip: '192.168.1.50', time: '3 hours ago', status: 'Warning' },
//   { user: 'Mike Chen', action: 'New Exam Created', ip: '192.168.1.51', time: '5 hours ago', status: 'Success' },
// ];

const statusBadgeClass = (status) => {
  const styles = {
    'Success': 'bg-emerald-50 text-emerald-600',
    'Error': 'bg-rose-50 text-rose-500',
    'Warning': 'bg-amber-50 text-amber-600',
    'Info': 'bg-sky-50 text-sky-600'
  };
  return styles[status] || 'bg-slate-100 text-slate-500';
};

const fetchDashboardStats = async () => {
  try {
    loading.value = true;
    const token = localStorage.getItem('token');

    if (!token) {
      console.error("Token not found. Please login.");
      return;
    }

    const response = await axios.get('http://127.0.0.1:8000/api/admin/dashboard-stats', {
      headers: { 
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    });

    if (response.data.success) {
      const data = response.data.data;
      
      // ২. এপিআই ডাটা অ্যাসাইন করা
      registrationData.value = data.registration_trend || [];
      todayExams.value = data.exams_today || [];

      activities.value = data.recent_activities || []; 

      // Stats আপডেট
      if (stats.value[0]) {
        stats.value[0].value = data.total_students || 0;
        stats.value[0].trend = `+${data.students_trend || 0}%`;
      }
      if (stats.value[1]) {
        stats.value[1].value = data.total_exams || 0;
        stats.value[1].trend = `+${data.exams_trend || 0}%`;
      }
      
      
      //stats.value[2].value = data.total_attempts;
      //stats.value[3].value = `$${data.total_revenue}`;
    }
  } catch (error) {
    console.error("Error fetching dashboard stats:", error);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchDashboardStats();
});

</script>