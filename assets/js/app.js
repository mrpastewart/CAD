import Vue from 'vue'

import Mdt from './components/mdt/mdt.vue'
import MdtCad from './components/mdt/pane-cad.vue'

Vue.component('mdt', Mdt);
Vue.component('mdt-cad', MdtCad);

const app = new Vue({
    el: '#app'
});
