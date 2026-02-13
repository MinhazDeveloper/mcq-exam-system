import { createRouter, createWebHistory } from 'vue-router';
import Login from '@/views/auth/Login.vue';
import Register from '@/views/auth/Register.vue'
import AdminMainLayout from '@/layouts/AdminMainLayout.vue';
import AdminDashboard from '@/views/admin/AdminDashboard.vue'
import Users from '@/views/admin/Users.vue'


import InstructorMainLayout from '@/layouts/InstructorMainLayout.vue';
import InstructorDashboard from '@/views/instructor/InstructorDashboard.vue'
import InstructorExam from '@/views/instructor/InstructorExam.vue'
import InstructorQuestion from '@/views/instructor/InstructorQuestion.vue'

import StudentDashboard from '@/views/student/StudentDashboard.vue'
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
    component: AdminMainLayout, // এই লেআউটের ভেতর চাইল্ড পেজগুলো বসবে
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


  //for questionlist
  { 
    path: '/admin/question/list',
    name: 'QuestionList',
    component: QuestionList, 
    meta: { requiresAuth: true, role: 'admin'}
  },
  //for question create
  { 
    path: '/admin/questionform', 
    name: 'AdminQuestionForm',
    component: AdminQuestionForm,
    meta: { requiresAuth: true, role: 'admin' } 
  },
  //for question edit
  { 
    path: '/admin/question/edit/:id',
    name: 'EditQuestion',
    component: AdminQuestionForm,
    props: true,
    meta: { requiresAuth: true, role: 'admin' }
  },
  
  //for student exam
  {
    path: '/student/exam/:id',
    name: 'StudentExam',
    component: StudentExam,
    meta: { requiresAuth: true }
  },
  // {
  //   path: '/admin/dashboard',
  //   component: AdminDashboard,
  //   meta: { requiresAuth: true, role: 'admin' }

  // },
  {
    path: '/student/dashboard',
    component: StudentDashboard,
    meta: { requiresAuth: true, role: 'student' }

  }
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