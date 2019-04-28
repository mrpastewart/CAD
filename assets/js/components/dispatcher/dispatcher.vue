<template lang="html">
    <div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Dispatcher</a>
            <button class="btn btn-outline-info my-2 my-sm-0" @click="createIncident">&plus; Create CAD</button>
            <div class="clearfix ml-auto" v-if="loading">
                <div class="spinner-border text-dark" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </nav>
        <div class="dispatcher-container" v-if="(incidents === null)">
            <div class="loading-panel__container">
                <div class="loading-panel__content">
                    <div class="lds-dual-ring"></div>
                </div>
            </div>
        </div>
        <div class="dispatcher-container" v-if="(incidents !== null)">
            <div class="container-fluid">
                <div style='margin:10px;background-color:#ff1227;' class="alert" role="alert" v-if="stateZeroUnits.length > 0">
                    <div class="text-center text-bold">
                        <i class="fas fa-bell state-zero-flash"></i>&nbsp;STATE ZERO&nbsp;<i class="fas fa-bell state-zero-flash"></i>
                        <h5 v-for="unit in stateZeroUnits">{{unit.name}} - {{unit.occupant_string}}</h5>
                    </div>
                </div>
                <div class="row">
                    <div v-if="!incident" class="dispatcher-panel__container col-lg-3 col-sm-6">
                        <div class="dispatcher-panel__title"
                        v-bind:class="{ 'dispatcher-panel__title--selected': (divisionFilter == null) }"
                        v-on:click="setDivisionFilter(null)">
                            All
                        </div>
                        <div class="dispatcher-panel__title"
                        v-for="division in divisions"
                        v-bind:class="{ 'dispatcher-panel__title--selected': (divisionFilter == division.id) }"
                        v-on:click="setDivisionFilter(division.id)">
                            {{division.shorthand_name}}
                        </div>
                        <div class="dispatcher-panel__block">
                            <div class="text-center text-bold" v-if="availableUnits.length == 0">
                                <h5>No units</h5>
                            </div>
                            <dispatcher-unit-badge
                                v-bind:unit="unit"
                                v-if="(unit.division_id == divisionFilter || divisionFilter == null)"
                                v-for="unit in availableUnits"
                            />
                        </div>
                    </div>
                    <dispatcher-incident-units
                    v-if="incident"
                    v-bind:incident="incident"
                    v-bind:available-units="availableUnits"
                    v-bind:assigned-units="assignedUnits"/>
                    <dispatcher-incident
                    v-if="incident"
                    v-bind:units="units"
                    v-bind:incident.sync="incident"/>
                    <div class="dispatcher-panel__container col-lg-9 col-sm-6" v-if="!incident">
                        <div class="dispatcher-panel__title dispatcher-panel__title--selected">
                            Incident list
                        </div>
                        <div class="dispatcher-panel__block dispatcher-panel__block--secondary" v-if="incidents.length == 0">
                            <div class="text-center text-bold">
                                <h5>No active incidents</h5>
                            </div>
                        </div>
                        <dispatcher-incident-row v-bind:incident="incident" v-for="incident in incidents" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {
    mapState
} from 'vuex'
import {
    mapGetters
} from 'vuex'

export default {
    props: ['shiftId'],
    data() {
        return {
            timer: null,
            lastUpdated: null,
            divisionFilter: null
        }
    },
    computed: {
        availableUnits: function() {
            return this.units.filter(function(unit) {
                return unit.incident_id === null;
            })
        },
        assignedUnits: function() {
            let incident = this.incident;
            return this.units.filter(function(unit) {
                return incident.id === unit.incident_id;
            })
        },
        ...mapState({
            incidents: state => state.incidents,
            units: state => state.units,
            incident: state => state.incident,
            loading: state => state.loading,
            divisions: state => state.divisions,
        }),
        ...mapGetters([
            'stateZeroUnits'
        ]),
    },
    mounted() {
        this.$store.dispatch('getDivisions');
        let self = this;
        this.$store.dispatch('setShiftId', {
            id: this.shiftId
        }).then(() => {
            self.refresh();
            self.timer = setInterval(this.refresh, 4000)
        })
    },
    methods: {
        refresh: function() {
            return this.$store.dispatch('updateDispatcher');
        },
        cancelAutoUpdate: function() {
            clearInterval(this.timer)
        },
        createIncident: function() {
            axios.post('/api/incidents', {
                    'shift_id': this.shiftId
                })
                .then((response) => {
                    this.refresh().then(() => {
                        this.$store.dispatch('setIncident', {
                            'id': response.data.id
                        });
                    });
                });
        },
        setDivisionFilter: function(id) {
            if (id) {
                this.divisionFilter = id;
            } else {
                this.divisionFilter = null;
            }
        }
    },
    beforeDestroy() {
        clearInterval(this.timer)
    }
}
</script>

<style lang="css" scoped>
</style>
