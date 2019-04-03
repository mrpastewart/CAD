<template lang="html">
    <div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Dispatcher</a>
            <button class="btn btn-outline-info my-2 my-sm-0" @click="createIncident">&plus; Create CAD</button>
            <div class="ml-auto navbar-brand">
                <small v-if="lastUpdated">Last updated: {{lastUpdated}}</small>
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
                <div style='margin:10px;background-color:#ff1227;' class="alert" role="alert" v-if="stateZeroUnits">
                    <div class="text-center text-bold">
                        <i class="fas fa-bell state-zero-flash"></i>&nbsp;STATE ZERO&nbsp;<i class="fas fa-bell state-zero-flash"></i>
                        <h5 v-for="unit in stateZeroUnits">{{unit.name}} - {{unit.occupant_string}}</h5>
                    </div>
                </div>
                <div class="row">
                    <div v-if="!incident" class="dispatcher-panel__container col-lg-3 col-sm-6">
                        <div class="dispatcher-panel__title dispatcher-panel__title--selected">
                            All
                        </div>
                        <div class="dispatcher-panel__title">
                            TP
                        </div>
                        <div class="dispatcher-panel__title">
                            CS
                        </div>
                        <div class="dispatcher-panel__title">
                            Traffic
                        </div>
                        <div class="dispatcher-panel__title">
                            Firearms
                        </div>
                        <div class="dispatcher-panel__title">
                            RMU
                        </div>
                        <div class="dispatcher-panel__block">
                            <dispatcher-unit-badge v-bind:unit="unit" :bus="bus" v-for="unit in units"/>
                        </div>
                    </div>
                    <dispatcher-incident-units v-if="incident" v-bind:input-incident="incident" :bus="bus"/>
                    <dispatcher-incident v-if="incident" v-bind:input-incident="incident" :bus="bus"/>
                    <div class="dispatcher-panel__container col-lg-9 col-sm-6" v-if="!incident">
                        <div class="dispatcher-panel__title dispatcher-panel__title--selected">
                            Incident list
                        </div>
                        <dispatcher-incident-row :bus="bus" v-bind:incident="incident" v-for="incident in incidents" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['shiftId'],
    data() {
        return {
            incidents: null,
            units: null,
            bus: new vue(),
            incident: null,
            timer: null,
            lastUpdated: null,
            stateZeroUnits: null
        }
    },
    mounted() {
        this.timer = setInterval(this.refresh, 4000)
        this.refresh();

        this.bus.$on('loadIncident', (args) => {
            if (args.id) {
                this.incident = args.incident;
            }
        });
        this.bus.$on('closeIncident', (args) => {
            this.incident = null;
        });
    },
    methods: {
        refresh: function() {
            axios.get('/api/shifts/'+this.shiftId+'/incidents')
                .then((response) => {
                    if (response.status == 200) {
                        this.incidents = response.data.incidents;
                        this.units = response.data.units;

                        let result = this.units.filter(unit => unit.status == 0);
                        if (result.length == 0) {
                            this.stateZeroUnits = null;
                        } else {
                            this.stateZeroUnits = result;
                        }
                    }
                    let currentTime = new Date();
                    let hours = currentTime.getHours();
                    let minutes = currentTime.getMinutes();
                    let seconds = currentTime.getSeconds();

                    if (minutes < 10) {
                        minutes = "0" + minutes;
                    }
                    if (seconds < 10) {
                        seconds = "0" + seconds;
                    }
                    this.lastUpdated = hours + ":" + minutes + ":" + seconds;
                }).catch((error) => {
                    if (error.response.status == 401) {
                        window.location.href = '/';
                    }
                });
        },
        cancelAutoUpdate: function() {
            clearInterval(this.timer)
        },
        createIncident: function() {
            this.incident = null;
            axios.post('/api/incidents', {
                    'shift_id': this.shiftId
                })
                .then((response) => {
                    this.incident = response.data;
                });
        }
    },
    beforeDestroy() {
        clearInterval(this.timer)
    }
}
</script>

<style lang="css" scoped>
</style>
