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
                <!-- <div style='margin:10px;background-color:#ff1227;' class="alert" role="alert">
                    <div class="text-center text-bold">
                        <i class="fas fa-bell state-zero-flash"></i>&nbsp;STATE ZERO&nbsp;<i class="fas fa-bell state-zero-flash"></i>
                        <h5>CN20 - [123] J.Doe</h5>
                    </div>
                </div> -->
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
                            <div class="unit-badge d-inline-flex unit-badge--auto-width">
                                <div>
                                    <div class="unit-badge__primary-text"><span class='state-code__box'>2</span>&nbsp;CN20</div>
                                    <div class="unit-badge__secondary-text">[910] P.Addison</div>
                                </div>
                                <div>
                                    <div class="unit-badge__role-list">
                                        <img class="unit-badge__role-image" src="img/role1/stndt.png" title='' alt="">
                                        <img class="unit-badge__role-image" src="img/role2/rmu.png" title="" alt="">
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="ml-auto">
                                    <div class="unit-badge__arrow-right">
                                        <i class="fas fa-angle-double-right"></i>
                                    </div>
                                </div>
                            </div>
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
    data() {
        return {
            incidents: null,
            bus: new vue(),
            incident: null,
            timer: null,
            lastUpdated: null,
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
            axios.get('/api/shifts/1/incidents')
            .then((response) => {
                if (response.status == 200) {
                    this.incidents = response.data
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
            });
        },
        cancelAutoUpdate: function() {
            clearInterval(this.timer)
        },
        createIncident: function() {
            this.incident = null;
            axios.post('/api/incidents', {'shift_id': 1})
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
