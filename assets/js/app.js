import Vue from 'vue'

import Mdt from './components/mdt/mdt.vue'
import MdtCad from './components/mdt/pane-cad.vue'
import Dispatcher from './components/dispatcher/dispatcher.vue'
import DispatcherCadList from './components/dispatcher/cad-list.vue'
import DispatcherIncidentRow from './components/dispatcher/incident-row.vue'
import DispatcherIncident from './components/dispatcher/incident.vue'
import DispatcherIncidentUnits from './components/dispatcher/incident-units.vue'
import DispatcherUnitBadge from './components/dispatcher/unit-badge.vue'

window.axios = require('axios');
window.vue = require('vue'); // fix this at some point

Vue.component('mdt', Mdt);
Vue.component('mdt-cad', MdtCad);
Vue.component('dispatcher', Dispatcher);
Vue.component('dispatcher-cad-list', DispatcherCadList);
Vue.component('dispatcher-incident-row', DispatcherIncidentRow);
Vue.component('dispatcher-incident', DispatcherIncident);
Vue.component('dispatcher-incident-units', DispatcherIncidentUnits);
Vue.component('dispatcher-unit-badge', DispatcherUnitBadge);

const app = new Vue({
    el: '#app'
});
