/* eslint-disable no-unused-vars */
import { createRouter, createWebHistory } from 'vue-router';
import LoginForm from './components/LoginForm.vue';
import RegistrationForm from './components/RegistrationForm.vue';
import DeskView from './components/DeskView.vue';
import LogoutPage from './components/LogoutPage.vue';
import FormView from './components/form/FormView.vue';
import ListView from './components/form/ListView.vue';
import NotFound from './pages/NotFound.vue';
import DeskPage from './pages/DeskPage.vue';
import SubscriptionPlan from './components/form/SubscriptionPlan.vue';
import CustomerForm from './components/form/CustomerForm.vue';
import SubscriptionForm from './components/form/SubscriptionForm.vue';
import InvoiceEntry from './components/form/InvoiceEntry.vue';
import PaymentForm from './components/form/PaymentForm.vue';

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
            name: 'list',
            component: ListView,
            props: true,
        },
        {
          path: 'Edit/subscription-plan/:recordId',
          name: 'subscription-plan',
          component: SubscriptionPlan,
          props: true,
        },
        {
          path: 'Edit/customers/:recordId',
          name: 'customers',
          component: CustomerForm,
          props: true,
        },
        {
          path: 'Edit/subscription/:recordId',
          name: 'subscription',
          component: SubscriptionForm,
          props: true,
        },
        {
          path: 'Edit/invoice-entry/:recordId',
          name: 'invoice-entry',
          component: InvoiceEntry,
          props: true,
        },
        {
          path: 'Edit/payment/:recordId',
          name: 'payment',
          component: PaymentForm,
          props: true,
        },
        // {
        //   path: 'Edit/:tableName/:recordId',
        //   name: 'edit',
        //   component: FormView,
        //   props: true,
        // }
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