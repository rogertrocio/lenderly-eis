import { createWebHistory, createRouter } from 'vue-router';

import MainLayout from '../layouts/MainLayout.vue';
import DashboardPage from '../pages/DashboardPage.vue';
import LoginLayout from '../layouts/LoginLayout.vue';
import ProfilePage from '../pages/auth/ProfilePage.vue';
import UserPage from '../pages/user/UserPage.vue';
import UserEditPage from '../pages/user/UserEditPage.vue';
import PageNotFound from '../pages/PageNotFound.vue';

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
        path: '/users/:id',
        name: 'UserEdit',
        component: UserEditPage,
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
  {
    path: '/:pathMatch(.*)*',
    name: 'PageNotFound',
    component: PageNotFound
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;
