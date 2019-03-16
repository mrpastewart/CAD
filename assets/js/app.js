import Vue from 'vue'

import Mdt from './components/mdt/mdt.vue'
import MdtCad from './components/mdt/pane-cad.vue'
import Dispatcher from './components/dispatcher/dispatcher.vue'

Vue.component('mdt', Mdt);
Vue.component('mdt-cad', MdtCad);
Vue.component('dispatcher', Dispatcher);

const app = new Vue({
    el: '#app'
});
