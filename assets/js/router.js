import Vue from 'vue'
import VueRouter from 'vue-router'
import store from './store.js'

import LoginPage from './pages/login.vue'
import HomePage from './pages/home.vue'
import Mdt from './components/mdt/mdt.vue'
import Dispatcher from './components/dispatcher/dispatcher.vue'

Vue.use(VueRouter)

const NotFound = { template: '<p>Page not found</p>' }
const routes = [
    {
        path: '/',
        component: LoginPage,
        name: 'login'
    },
    {
        path: '/home',
        name: 'home',
        component: HomePage
    },
    {
        path: '/mdt',
        name: 'mdt',
        component: Mdt
    },
    {
        path: '/dispatcher/:shiftId',
        name: 'dispatcher',
        component: Dispatcher,
        props: true
    }
];

const router = new VueRouter({
    routes
});

export default router;
