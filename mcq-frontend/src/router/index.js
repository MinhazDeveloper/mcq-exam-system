import { createRouter, createWebHistory } from 'vue-router';
import Login from '@/views/auth/Login.vue';
import Register from '@/views/auth/Register.vue'
// import AdminDashboard from '@/views/admin/AdminDashboard.vue'
// import ExamPage from '@/views/admin/ExamPage.vue'
import AdminQuestionForm from '@/views/admin/AdminQuestionForm.vue' 
import QuestionList from '@/views/admin/QuestionList.vue'

import Dashboard from '@/views/admin/Dashboard.vue'; 
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
  // { 
  //   path: '/admin/dashboard', 
  //   name: 'AdminDashboard',
  //   component: AdminDashboard,
  //   meta: { requiresAuth: true, role: 'admin' } 
  // },
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
    props: true },
  // { 
  //   path: '/exam', 
  //   name: 'ExamPage',
  //   component: ExamPage,
  //   meta: { requiresAuth: true, role: 'student' } 
  // },
  {
    path: '/dashboard',
    component: Dashboard,
    meta: { requiresAuth: true }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
  const auth = useAuthStore();

  //logged in user → block login page
  if (to.name === 'login' && auth.isAuthenticated) {
    return auth.user?.role === 'admin'
      ? next('/admin/questionform')
      : next('/dashboard')
  }
  // auth check
  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return next('/login');
  }

  // role check
  if (to.meta.role) {
    if (!auth.user) {
      return next('/login')
    }

    if (auth.user.role !== to.meta.role) {
      return auth.user.role === 'admin'
        ? next('/admin/questionform')
        : next('/dashboard')
    }
  }

  next();
});

export default router;
