import { createRouter, createWebHistory } from 'vue-router';
import Login from '@/views/auth/Login.vue';
import Register from '@/views/auth/Register.vue'
//admin
import AdminMainLayout from '@/layouts/AdminMainLayout.vue';
import AdminDashboard from '@/views/admin/AdminDashboard.vue'
import Users from '@/views/admin/Users.vue'
import AdminExam from '@/views/admin/AdminExam.vue'
import AdminExamHistory from '@/views/admin/ExamHistory.vue'
//instructor
import InstructorMainLayout from '@/layouts/InstructorMainLayout.vue';
import InstructorDashboard from '@/views/instructor/InstructorDashboard.vue'
import InstructorExam from '@/views/instructor/InstructorExam.vue'
import InstructorQuestion from '@/views/instructor/InstructorQuestion.vue'
//student
import StudentMainLayout from '@/layouts/StudentMainLayout.vue';
// import StudentDashboard from '@/views/student/StudentDashboard.vue'
import StudentExamList from '@/views/student/StudentExamList.vue'
import ExamResult from '@/views/student/ExamResult.vue'
import ExamHistory from '@/views/student/ExamHistory.vue'

// import ExamPage from '@/views/admin/ExamPage.vue'
import AdminQuestionForm from '@/views/admin/AdminQuestionForm.vue' 
import QuestionList from '@/views/admin/QuestionList.vue'
import StudentExam from '@/views/student/StudentExam.vue'

import { useAuthStore } from '@/stores/auth';

const routes = [
  {
  path: '/',
  redirect: '/login'
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
  },
  { path: '/register',
    name: 'register',
    component: Register 
  },
  //for super admin
  {
    path: '/admin',
    component: AdminMainLayout,
    meta: { requiresAuth: true, role: 'admin' },
    children: [
      {
        path: 'dashboard', 
        name: 'AdminDashboard',
        component: AdminDashboard
      },
      {
        path: 'users', 
        name: 'Users',
        component: Users
      },
      {
        path: 'exams', 
        name: 'AdminExam',
        component: AdminExam
      },
      {
        path: 'exam-history', 
        name: 'AdminExamHistory',
        component: AdminExamHistory
      },
      
    ]
  },
  //for instructor
  {
    path: '/instructor',
    component: InstructorMainLayout,
    meta: { requiresAuth: true, role: 'instructor' },
    children: [
      {
        path: 'dashboard', 
        name: 'InstructorDashboard',
        component: InstructorDashboard
      },
      {
        path: 'exams', 
        name: 'InstructorExam',
        component: InstructorExam
      },

      {
        path: 'question', 
        name: 'InstructorQuestion',
        component: InstructorQuestion
      },
    ]
  },
  //for student
  {
    path: '/student',
    component: StudentMainLayout,
    meta: { requiresAuth: true, role: 'student' },
    children: [
      // {
      //   path: 'dashboard', 
      //   name: 'StudentDashboard',
      //   component: StudentDashboard
      // },
      {
        path: 'dashboard', 
        name: 'StudentExams',
        component: StudentExamList
      },
      {
        path: 'exam/:id',
        name: 'StudentExam',
        component: StudentExam,
        props: true
      },
      {
        path: 'exam-result/:id',
        name: 'ExamResult',
        component: ExamResult, 
        props: true
      },
      {
        path: 'exam-history', 
        name: 'ExamHistory',
        component: ExamHistory
      },

    ]
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

const dashboardMap = {
  admin: '/admin/dashboard',
  instructor: '/instructor/dashboard',
  student: '/student/dashboard'
}

router.beforeEach((to, from, next) => {
  const auth = useAuthStore()

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return next('/login')
  }

  if (to.name === 'login' && auth.isAuthenticated) {
    return next(dashboardMap[auth.user.role])
  }

  if (to.meta.role && auth.user.role !== to.meta.role) {
    return next(dashboardMap[auth.user.role])
  }

  next()
})

export default router;