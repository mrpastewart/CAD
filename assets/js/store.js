import Vuex from 'vuex'
import Vue from 'vue'

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        incidents: null,
        units: [],
        incident: null,
        timer: null,
        loading: false,
        shiftId: false,
        divisions: [],
        notifications: [],
        user: null,
        offline: false
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
        },
        SET_USER(state, user) {
            state.user = user;
        },
        ADD_NOTIFICATION(state, notification) {
            state.notifications.push(notification);
        },
        DELETE_NOTIFICATION(state, notificationId) {
            state.notifications.splice(notificationId, 1);
        },
        SET_OFFLINE(state, bool) {
            state.offline = bool;
        }
    },
    actions: {
        addNotification(context, params) {
            context.commit("ADD_NOTIFICATION", {
                'type': (params['type'] ? params['type'] : 'error'),
                'text': (params['text'] ? params['text'] : 'error')
            });
        },
        deleteNotification(context, id) {
            context.commit('DELETE_NOTIFICATION', id)
        },
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
        getUser(context) {
            return new Promise((resolve, reject) => {
                axios.get('/api/user')
                    .then((response) => {
                        if (response.status == 200) {
                            context.commit('SET_USER', response.data);
                            resolve(response);
                        }
                    }).catch((error) => {
                        reject(error);
                    });
            });
        },
        getDivisions(context) {
            return new Promise((resolve, reject) => {
                axios.get('/api/divisions')
                    .then((response) => {
                        if (response.status == 200) {
                            context.commit('SET_DIVISIONS', response.data)
                            resolve(response);
                        }
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
});

export default store;
