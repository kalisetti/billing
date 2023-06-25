import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap/dist/js/bootstrap.js'
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue';
import LoginForm from './components/LoginForm.vue';
import RegistrationForm from './components/RegistrationForm.vue';
import DeskView from './components/DeskView.vue';
import NotFound from './pages/NotFound.vue';

const routes = [
  { path: '/login', component: LoginForm },
  { path: '/register', component: RegistrationForm },
  { path: '/desk', component: DeskView },
  { path: '/:notFound(.*)', component: NotFound },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

const app = createApp(App);
app.use(router);
app.mount('#app');