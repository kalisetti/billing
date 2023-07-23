/* eslint-disable no-unused-vars */
// Load views
import { createRouter, createWebHistory } from 'vue-router';
import DeskView from './components/views/DeskView.vue';
import ListView from './components/views/ListView.vue';
// Load forms
import LoginForm from './components/forms/LoginForm.vue';
import RegistrationForm from './components/forms/RegistrationForm.vue';
import LogoutForm from './components/forms/LogoutForm.vue';
import FormView from './components/forms/FormView.vue';
import SubscriptionPlan from './components/forms/SubscriptionPlan.vue';
import CustomerForm from './components/forms/CustomerForm.vue';
import SubscriptionForm from './components/forms/SubscriptionForm.vue';
import InvoiceEntry from './components/forms/InvoiceEntry.vue';
import PaymentEntry from './components/forms/PaymentEntry.vue';
// Load pages
import NotFound from './components/pages/NotFound.vue';

const routes = [
  { path: '/', redirect: '/login'},
  { path: '/login', name: 'login', components: { default: LoginForm } },
  { path: '/register', name: 'register', component: RegistrationForm },
  { 
    path: '/desk', 
    name: 'desk', 
    children: [
        {
            path: '',
            component: DeskView,
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
          component: PaymentEntry,
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
  { path: '/logout', name: 'logout', component: LogoutForm},
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