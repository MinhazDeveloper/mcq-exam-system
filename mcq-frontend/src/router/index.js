import { createRouter, createWebHistory } from 'vue-router';
import Login from '@/views/auth/Login.vue';
import Register from '@/views/auth/Register.vue'
import AdminDashboard from '@/views/admin/AdminDashboard.vue'
import ExamPage from '@/views/admin/ExamPage.vue'

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
  { 
    path: '/admin/dashboard', 
    name: 'AdminDashboard',
    component: AdminDashboard,
    meta: { requiresAuth: true, role: 'admin' } 
  },
  { 
    path: '/exam', 
    name: 'ExamPage',
    component: ExamPage,
    meta: { requiresAuth: true, role: 'student' } 
  },
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

  // already logged in → login page block
  if (to.name === 'login' && auth.isAuthenticated) {
    return next('/dashboard');
  }

  // auth check
  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return next('/login');
  }

  // role check
  if (to.meta.role && auth.user?.role !== to.meta.role) {
    return next('/dashboard');
  }

  next();
});



export default router;
