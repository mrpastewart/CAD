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
            <div v-if='page == "update-status"'>
                <div class="d-flex justify-content-center flex-wrap flex-fill">
                    <div class="btn btn-light m-2" @click='updateStatus(2)'>
                        <span class='state-code__box state-code__box--2'>2</span>&nbsp;Available
                    </div>
                    <div class="btn btn-light m-2" @click='updateStatus(4)'>
                        <span class='state-code__box state-code__box--4'>4</span>&nbsp;Welfare break
                    </div>
                    <div class="btn btn-light m-2" @click='updateStatus(5)' v-if="incident">
                        <span class='state-code__box state-code__box--5'>5</span>&nbsp;On Route
                    </div>
                    <div class="btn btn-light m-2" @click='updateStatus(6)' v-if="incident">
                        <span class='state-code__box state-code__box--6'>6</span>&nbsp;On Scene
                    </div>
                    <div class="btn btn-light m-2" @click='updateStatus(7)'>
                        <span class='state-code__box state-code__box--7'>7</span>&nbsp;Available Committed
                    </div>
                    <div class="btn btn-light m-2" @click='updateStatus(8)'>
                        <span class='state-code__box state-code__box--8'>8</span>&nbsp;Unavailable
                    </div>
                    <div class="btn btn-light m-2" @click='updateStatus(9)'>
                        <span class='state-code__box state-code__box--9'>9</span>&nbsp;Transporting
                    </div>
                    <div class="btn btn-light m-2" @click='updateStatus(10)'>
                        <span class='state-code__box state-code__box--10'>10</span>&nbsp;Technical issues
                    </div>
                    <div class="btn btn-light m-2" @click='updateStatus(11)'>
                        <span class='state-code__box state-code__box--11'>11</span>&nbsp;Off Duty
                    </div>
                </div>
            </div>
            <div v-if='page == "incident"'>
                <div v-if="incident">
                    <h3 class='text-center'>{{incident.title}}</h3>
                    <div class="d-flex justify-content-center flex-wrap">
                        <div class='pr-2 pl-2'>CAD: {{incident.id}}</div>
                        <div class='pr-2 pl-2'>Channel: {{incident.interop}}</div>
                        <div class='pr-2 pl-2'>Response: <span class="badge badge-danger">{{incident.grading}} grade</span></div>
                    </div>
                    <div class='p-2 text-center'>
                        <p style="white-space: pre-wrap;">{{incident.details}}</p>
                    </div>
                    <div class="p-2">
                        <div class="d-flex justify-content-center flex-wrap flex-fill" v-if="updateOption == null">
                            <button type="button" class="m-1 btn btn-success" @click='updateOption = "note"'>
                                Add note
                            </button>
                            <button type="button" class="m-1 btn btn-primary" @click='updateOption = "close"'>
                                Close incident
                            </button>
                        </div>
                        <div v-if="updateOption == 'note' || updateOption == 'close'">
                            <div class="form-group">
                                <label>Content</label>
                                <textarea class="form-control" v-model="updateText" rows="3"></textarea>
                            </div>
                            <div class="text-center">
                                <button type="button" class="m-1 btn btn-success" @click='submitUpdate'>
                                    Submit update
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if='page == "main"'>
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
                    <div class="dispatcher-panel__container ">
                        <div @click='toggleComments'>
                            <i class="fas" v-bind:class="{ 'fa-chevron-down': show_comments, 'fa-chevron-right': !show_comments}"></i> Comments
                        </div>
                        <div class="dispatcher-panel__block dispatcher-panel__block--secondary" v-show='show_comments'>
                            <div class="dispatcher-panel__body">
                                <div class="">
                                    <div class='chat-message__container' v-for='log in logs'>
                                        <div class="chat-message-author" v-if='log.type == 1'>
                                            <span class="badge badge-pill badge-primary">System</span>
                                        </div>
                                        <div class="chat-message-author" v-if='log.type == 2'>
                                            <span class="badge badge-pill badge-primary">{{log.type}}</span>
                                        </div>
                                        <div class="chat-message__message pre-wrap">
                                            {{log.details}}
                                        </div>
                                        <div class="chat-message__time">
                                            {{log.created_at}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center flex-wrap flex-fill">
                    <p class='m-1 text-center' v-if='incident === null && unit.status != 0'>
                        No current assignment
                    </p>
                    <button type="button" class="m-1 btn btn-primary" v-if='unit.status === 5' @click='updateStatus(6)'>
                        <i class="far fa-check-circle"></i>&nbsp;Mark On scene
                    </button>
                    <button type="button" class="m-1 btn btn-success" v-if='unit.status === 0' @click='cancelPanicButton()'>
                        <i class="far fa-bell-slash"></i>&nbsp;Cancel panic button
                    </button>
                </div>
            </div>
            <br>
            <div class="d-flex justify-content-center flex-wrap" v-if="page !== 'main'">
                <button class='square-menu__button btn btn-light' type='button' @click="changePage('main')"><i class="fas fa-home"></i></button>
            </div>
            <div class="d-flex justify-content-center flex-wrap" v-if="page === 'main'">
                <button class='square-menu__button btn btn-light' type='button' @click="changePage('update-status')"><i class="far fa-user"></i></button>
                <button class='square-menu__button btn btn-light' type='button' v-if='incident' @click="changePage('incident')"><i class="far fa-edit"></i></button>
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
            stateZeroUnits: null,
            page: 'main',
            show_comments: false,
            updateText: '',
            updateOption: null
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
            this.page = 'main';
            let self = this;
            axios.patch('/api/units/'+this.unit.id, {
                'status': statusCode
            }).then(function() {
                self.refresh();
                self.changePage('main');
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
        },
        changePage: function(page) {
            this.page = page
            this.updateOption = null;
        },
        toggleComments: function() {
            this.show_comments = !this.show_comments
        },
        submitUpdate: function () {
            let self = this;
            axios.post('/api/incidents/'+this.incident.id+'/notes', {
                'content': this.updateText,
                'close' : (this.updateOption == 'close')
            }).then(function() {
                self.changePage('main');
                self.refresh();
                self.updateText = '';
            });
        }
    },
    computed: {
        stateStyles: function () {
            let object = {
                'state-zero-flash': (this.unit.status == 0)
            }
            object['state-code__box--'+this.unit.status] = true;
            return object;
        },
        logs: function() {
            let logs = []
            if (this.incident.logs) {
                logs = this.incident.logs.reverse();
            }
            return logs;
        }
    },
    beforeDestroy() {
        clearInterval(this.timer)
    }
}
</script>

<style lang="css" scoped>
</style>
