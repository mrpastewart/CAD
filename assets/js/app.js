import 'es6-promise/auto'

window.axios = require('axios');
import Vue from 'vue'
import Vuex from 'vuex'
window.vue = Vue;

import Mdt from './components/mdt/mdt.vue'
import MdtCad from './components/mdt/pane-cad.vue'
import Dispatcher from './components/dispatcher/dispatcher.vue'
import DispatcherCadList from './components/dispatcher/cad-list.vue'
import DispatcherIncidentRow from './components/dispatcher/incident-row.vue'
import DispatcherIncident from './components/dispatcher/incident.vue'
import DispatcherIncidentUnits from './components/dispatcher/incident-units.vue'
import DispatcherUnitBadge from './components/dispatcher/unit-badge.vue'
import LoginForm from './components/login-form.vue'
import Notifications from './components/notifications.vue'


Vue.use(Vuex);
Vue.component('mdt', Mdt);
Vue.component('mdt-cad', MdtCad);
Vue.component('dispatcher', Dispatcher);
Vue.component('dispatcher-cad-list', DispatcherCadList);
Vue.component('dispatcher-incident-row', DispatcherIncidentRow);
Vue.component('dispatcher-incident', DispatcherIncident);
Vue.component('dispatcher-incident-units', DispatcherIncidentUnits);
Vue.component('dispatcher-unit-badge', DispatcherUnitBadge);
Vue.component('login-form', LoginForm);
Vue.component('notifications', Notifications);

const store = new Vuex.Store({
    state: {
        incidents: null,
        units: [],
        incident: null,
        timer: null,
        loading: false,
        shiftId: false,
        divisions: []
    },
    mutations: {
        SET_LOADING(state, status) {
            state.loading = status;
        },
        SET_INCIDENTS(state, incidents) {
            state.incidents = incidents;
        },
        SET_INCIDENT(state, incident) {
            state.incident = incident;
        },
        SET_UNITS(state, units) {
            state.units = units;
        },
        SET_SHIFT_ID(state, shiftId) {
            state.shiftId = shiftId;
        },
        SET_DIVISIONS(state, divisions) {
            state.divisions = divisions;
        }
    },
    actions: {
        setShiftId(context, params) {
            context.commit('SET_SHIFT_ID', params.id)
        },
        setIncident(context, params) {
            context.commit('SET_INCIDENT', context.state.incidents.find(function(incident) {
                return incident.id === params.id;
            }));
        },
        clearIncident(context) {
            context.commit('SET_INCIDENT', null);
        },
        getDivisions(context) {
            return new Promise((resolve, reject) => {
                axios.get('/api/divisions')
                    .then((response) => {
                        if (response.status == 200) {
                            context.commit('SET_DIVISIONS', response.data)
                            resolve(response);
                        };
                    }).catch((error) => {
                        if (error.response.status == 401) {
                            window.location.href = '/';
                        }
                        reject(error);
                    });
            });
        },
        updateDispatcher(context) {
            return new Promise((resolve, reject) => {
                context.commit('SET_LOADING', true);
                axios.get('/api/shifts/' + context.state.shiftId + '/incidents')
                    .then((response) => {
                        if (response.status == 200) {
                            context.commit('SET_INCIDENTS', response.data.incidents);
                            context.commit('SET_UNITS', response.data.units);

                            if (context.state.incident) {
                                // Manually update the incident rendered as doesn't auto update
                                context.dispatch('setIncident', {
                                    id: context.state.incident.id
                                });
                            }
                            context.commit('SET_LOADING', false);
                            resolve(response);
                        }
                    }).catch((error) => {
                        if (error.response.status == 401) {
                            context.commit('SET_LOADING', false);
                            window.location.href = '/';
                        }
                        reject(error);
                    });
            });
        }
    },
    getters: {
        stateZeroUnits: state => {
            return state.units.filter(unit => unit.status == 0);
        }
    }
})

const app = new Vue({
    el: '#app',
    store
});
