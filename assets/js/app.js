import 'es6-promise/auto'

window.axios = require('axios');
import Vue from 'vue';
import Vuex from 'vuex';
window.vue = Vue;

import router from './router.js'
import store from './store.js'

const app = new Vue({
    el: '#app',
    store,
    router: router,
    mounted() {
        if (this.$router.currentRoute.name != 'login') { // reset to login on load
            this.$router.push({name:'login'});
        }
    }
})
