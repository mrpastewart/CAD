<template lang="html">
    <div class="bordered-container bordered-container--flex-width">
        <div class="bordered-container__heading">{{shift.name}}</div>
        <div class="bordered-container__content" v-if="!unitSignup">
            <div>
                <span><i class="fas fa-headset"></i>&nbsp;{{shift.dispatcher_count}}</span>&nbsp;&nbsp;
                <i class="fas fa-users"></i>&nbsp;{{shift.unit_count}}&nbsp;&nbsp;
                <i class="fas fa-clipboard"></i>&nbsp;{{shift.incident_count}}
            </div>
            <div class='d-flex justify-content-center mt-3'>
                <button class="btn btn-block btn-primary" @click='unitSignup = true;'>Sign in</button>
            </div>
            <div class='d-flex justify-content-center mt-3'>
                <button class="btn btn-block btn-secondary" @click='$router.push({name:"dispatcher", params:{shiftId: shift.id}})'>Dispatcher sign in</button>
            </div>
        </div>
        <div class="bordered-container__content" v-if="unitSignup">
            <div class="alert alert-danger mt-3 mb-1" role="alert" v-if="error">
                <div class="text-center text-bold">
                    <p>{{error}}</p>
                </div>
            </div>
            <form>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" v-model="passenger">
                    <label class="form-check-label">
                        I am accompanying another officer
                    </label>
                </div>
                <div class="bordered-container" v-if="passenger">
                    <div class="bordered-container__heading">
                        <h5>Vehicle details</h5>
                    </div>
                    <div class="bordered-container__content">
                        <div class="form-group">
                            <label>Callsign</label>
                            <select type="text" class="form-control" v-model="passengerCallsign">
                                <option value="" selected disabled>Select a callsign</option>
                                <option v-for="unit in units">{{unit.name}}</option>
                            </select>
                            <small class="form-text">The person you are accompanying must have already signed up for you to see the callsign in the list.</small>
                        </div>
                    </div>
                </div>
                <div class="bordered-container" v-if="!passenger">
                    <div class="bordered-container__heading">
                        <h5>Vehicle details</h5>
                    </div>
                    <div class="bordered-container__content">
                        <div class="form-group">
                            <label>Callsign</label>
                            <input type="text" class="form-control" placeholder="CN20" v-model='callsign'>
                        </div>
                        <div class="form-group">
                            <label>Department</label>
                            <select type="text" class="form-control" v-model="division">
                                <option value="" selected disabled>Select a division</option>
                                <option v-for="dloop in divisions" v-bind:value="dloop.id">{{dloop.name}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button @click="login" class="btn btn-primary">Sign in</button>
                </div>
            </form>
        </div>
  </div>

</template>

<script>
import { mapState } from 'vuex'

export default {
    props: ['shift'],
    computed: mapState({
        divisions: 'divisions'
    }),
    data: function()
    {
        return {
            passenger: false,
            shiftId: this.shift.id,
            division: "",
            callsign: "",
            passengerCallsign: "",
            error: false,
            unitSignup: false,
            units: []
        };
    },
    mounted: function() {
        this.getUnits();
    },
    methods: {
        getUnits() {
            this.error = false;
            axios.get('/api/shifts/'+this.shiftId+'/units').catch((error) => {
                if (error.response.data.error) {
                    this.error = error.response.data.error;
                }
            }).then((response) => {
                this.units = response.data;
            });
        },
        login(event) {
            event.preventDefault();
            this.error = false;
            axios.post('/auth/signup', {
                'passenger': this.passenger,
                'shift': this.shiftId,
                'division': this.division,
                'callsign': (this.passenger ? this.passengerCallsign : this.callsign),
            }).catch((error) => {
                if (error.response.data.error) {
                    this.error = error.response.data.error;
                }
            }).then((response) => {
                if (response.status === 200) {
                    this.$router.push({name:'mdt'})
                }
            });
        },
    }
}
</script>

<style lang="css" scoped>
</style>
