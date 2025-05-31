import { createWebHistory, createRouter } from 'vue-router';

import MainLayout from '../layouts/MainLayout.vue';
import DashboardPage from '../pages/DashboardPage.vue';
import EmployeePage from '../pages/employee/EmployeePage.vue';

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
        path: '/employees',
        name: 'Employees',
        component: EmployeePage,
      },
    ]
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;
