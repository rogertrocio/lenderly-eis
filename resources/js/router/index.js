import { createWebHistory, createRouter } from 'vue-router';

import MainLayout from '../layouts/MainLayout.vue';
import DashboardPage from '../pages/DashboardPage.vue';
import EmployeePage from '../pages/employee/EmployeePage.vue';
import LoginLayout from '../layouts/LoginLayout.vue';
import ProfilePage from '../pages/auth/ProfilePage.vue';
import UserPage from '../pages/user/UserPage.vue';

const routes = [
  {
    path: '/',
    component: MainLayout,
    children: [
      {
        path: '/',
        name: 'Dashboard',
        component: DashboardPage,
      },
      {
        path: '/users',
        name: 'User',
        component: UserPage,
      },
      {
        path: '/employees',
        name: 'Employee',
        component: EmployeePage,
      },
      {
        path: '/profile',
        name: 'Profile',
        component: ProfilePage,
      }
    ]
  },
  {
    path: '/login',
    name: 'Login',
    component: LoginLayout,
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;
