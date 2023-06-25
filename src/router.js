import { createRouter, createWebHistory } from 'vue-router';
import LoginForm from './components/LoginForm.vue';
import RegistrationForm from './components/RegistrationForm.vue';
import DeskView from './components/DeskView.vue';
import NotFound from './pages/NotFound.vue';
import PageHeader from './pages/PageHeader.vue';
import PageFooter from './pages/PageFooter.vue';

const routes = [
  { path: '/', redirect: '/login'},
  { path: '/login', component: LoginForm },
  { path: '/register', component: RegistrationForm },
  { 
    path: '/desk', 
    components: { default: DeskView, header: PageHeader, footer: PageFooter },
  },
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

// router.afterEach(function (to, from) {
//     // sending analytics data
//     console.log('Global router.afterEach(to, from)', to, from);
// });

// router.beforeEach(function (to, from, next) {
//     console.log('Global router.beforeEach()');
//     if (to.meta.needsAuth) {
//         console.log('Needs auth!');
//     }
//     next();
// });
  

export default router;