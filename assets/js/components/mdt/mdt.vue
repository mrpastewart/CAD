<template lang="html">
    <div class="mdt-container">
        <div class="mdt-panel" v-if="!unit">
            <div class="clearfix text-center mt-4">
                <div class="spinner-border text-light" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <div class="mdt-panel" v-if='unit'>
            <div class="d-flex justify-content-center flex-wrap">
                <div class="m-1"><span class='state-code__box' v-bind:class="stateStyles">{{unit.status}}</span>&nbsp;{{unit.name}}</div>
                <div class="ml-auto m-1 ">
                    <div class="clearfix d-inline-block" v-if="loading">
                        <div class="spinner-border spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger" @click='updateStatus(0)' v-if='unit.status !== 0'><i class="fas fa-bell"></i>&nbsp;Panic button&nbsp;</button>
                </div>
            </div>
            <div style='margin:10px;background-color:#ff1227;' class="alert" role="alert" v-if="stateZeroUnits">
                <div class="text-center text-bold">
                    <i class="fas fa-bell state-zero-flash"></i>&nbsp;STATE ZERO&nbsp;<i class="fas fa-bell state-zero-flash"></i>
                    <h5 v-for="unit in stateZeroUnits">{{unit.name}} - {{unit.occupant_string}}</h5>
                </div>
            </div>
            <!-- <div class="d-flex justify-content-center flex-wrap flex-fill">
    <button type="button" class="m-1 btn btn-success">Available</button>
    <button type="button" class="m-1 btn btn-info">Ref break</button>
    <button type="button" class="m-1 btn btn-info">Game crash</button>
    </div> -->
            <div v-if="incident && unit.status !== 0">
                <h3 class='text-center'>{{incident.title}}</h3>
                <div class="d-flex justify-content-center flex-wrap">
                    <div class='pr-2 pl-2'>CAD: {{incident.id}}</div>
                    <div class='pr-2 pl-2'>Channel: {{incident.interop}}</div>
                    <div class='pr-2 pl-2'>Response: <span class="badge badge-danger">{{incident.grading}} grade</span></div>
                </div>
                <div class='p-2 text-center'>
                    <p style="white-space: pre-wrap;">{{incident.details}}</p>
                </div>
            </div>
            <div class="d-flex justify-content-center flex-wrap flex-fill">
                <button type="button" class="m-1 btn btn-primary" v-if='unit.status === 5' @click='updateStatus(6)'>
                    <i class="far fa-check-circle"></i>&nbsp;Mark On scene
                </button>
                <button type="button" class="m-1 btn btn-success" v-if='unit.status === 0' @click='cancelPanicButton()'>
                    <i class="far fa-bell-slash"></i>&nbsp;Cancel panic button
                </button>
            </div>
            <br>
            <div class="d-flex justify-content-center flex-wrap">
                <button class='square-menu__button btn btn-light' type='button'><i class="far fa-edit"></i></button>
                <button class='square-menu__button btn btn-light' type='button'><i class="far fa-user"></i></button>
                <button class='square-menu__button btn btn-light' type='button'><i class="far fa-envelope"></i></button>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    data() {
        return {
            user: null,
            unit: null,
            units: null,
            incident: null,
            loading: false,
            stateZeroUnits: null
        }
    },
    mounted() {
        this.timer = setInterval(this.refresh, 4000)
        this.refresh();
    },
    methods: {
        refresh: function() {
            this.loading = true;
            axios.get('/api/mdt')
                .then((response) => {
                    if (response.status == 200) {
                        this.incident = response.data.incident;
                        this.user = response.data.user;
                        this.unit = response.data.unit;
                        this.units = response.data.units;

                        let result = this.units.filter(unit => unit.status == 0);
                        if (result.length == 0) {
                            this.stateZeroUnits = null;
                        } else {
                            this.stateZeroUnits = result;
                        }

                        this.loading = false;
                    }

                }).catch((error) => {
                    if (error.response.status == 401) {
                        window.location.href = '/';
                    }
                });
        },
        cancelAutoUpdate: function() {
            clearInterval(this.timer)
        },
        updateStatus: function (statusCode) {
            let self = this;
            axios.patch('/api/units/'+this.unit.id, {
                'status': statusCode
            }).then(function() {
                self.refresh();
            });
        },
        cancelPanicButton: function () {
            // if on cad then set you to on scene when cancelled
            // if not set available
            if (this.incident) {
                this.updateStatus(6);
            } else {
                this.updateStatus(2);
            }
        }
    },
    computed: {
        stateStyles: function () {
            let object = {
                'state-zero-flash': (this.unit.status == 0)
            }
            object['state-code__box--'+this.unit.status] = true;
            return object;
        }
    },
    beforeDestroy() {
        clearInterval(this.timer)
    }
}
</script>

<style lang="css" scoped>
</style>
