/* eslint-disable no-unused-vars */
import { createRouter, createWebHistory } from 'vue-router';
import LoginForm from './components/LoginForm.vue';
import RegistrationForm from './components/RegistrationForm.vue';
import DeskView from './components/DeskView.vue';
import LogoutPage from './components/LogoutPage.vue';
import ListView from './components/form/ListView.vue';
import NotFound from './pages/NotFound.vue';
import DeskPage from './pages/DeskPage.vue';

const routes = [
  { path: '/', redirect: '/login'},
  { path: '/login', name: 'login', components: { default: LoginForm } },
  { path: '/register', name: 'register', component: RegistrationForm },
  { 
    path: '/desk', 
    name: 'desk', 
    components: { default: DeskView },
    children: [
        {
            path: '',
            component: DeskPage,
        },
        {
            path: 'List/:tableName',
            component: ListView,
            props: true,
        }
    ] 
  },
  { path: '/logout', name: 'logout', component: LogoutPage},
  { path: '/:notFound(.*)', component: NotFound },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
        return savedPosition;
    }
    return { left: 0, top: 0 };
  }
});

export default router;