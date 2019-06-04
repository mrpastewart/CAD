<template lang="html">
    <auth-template>
        <div class='text-center' v-if='user'>
            <h2>CAD - {{user.name}}</h2>
            <button class='mt-3 btn btn-block btn-lg btn-primary' type="button" name="button" v-if="user.unit_id != null" @click='$router.push({name:"mdt"})'>Open MDT</button>
            <button class='mt-3 btn btn-block btn-lg btn-primary' type="button" name="button" v-if="user.unit_id != null">Open dispatcher</button>
            <button class='mt-3 btn btn-block btn-lg btn-danger' type="button" name="button" v-if="user.unit_id != null">Logout</button>
        </div>
        <!-- <br>
        <br>
        <form  style='padding:10px;'>
            <div class="form-group" >
                <label>Shift</label>
                <select type="text" class="form-control" v-model='shift'>
                    <option value="" selected disabled>Select a shift</option>
                    <option v-for="shift in shifts" v-bind:value="shift.id">{{shift.name}}</option>
                </select>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" v-model="passenger">
                <label class="form-check-label">
                    I am accompanying another officer
                </label>
            </div>
            <div class="bordered-container" v-if="passenger">
                <div class="bordered-container__heading"><h5>Vehicle details</h5></div>
                <div class="bordered-container__content">
                    <div class="form-group">
                        <label>Callsign</label>
                        <select type="text" class="form-control" v-model="passengerCallsign">
                            <option value="" selected disabled>Select a callsign</option>
                            <option>CN20</option>
                        </select>
                        <small class="form-text">The person you are accompanying must have already signed up for you to see the callsign in the list.</small>
                    </div>
                </div>
            </div>
            <div class="bordered-container" v-if="!passenger">
                <div class="bordered-container__heading"><h5>Vehicle details</h5></div>
                <div class="bordered-container__content">
                    <div class="form-group">
                        <label>Callsign</label>
                        <input type="text" class="form-control" placeholder="CN20" v-model='callsign'>
                    </div>
                    <div class="form-group" >
                        <label>Department</label>
                        <select type="text" class="form-control" v-model="division">
                            <option value="" selected disabled>Select a division</option>
                            <option>Frontline Policing</option>
                            <option>Crime Squad</option>
                            <option>Traffic</option>
                            <option>Trojan</option>
                            <option>RMU</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button @click="login" class="btn btn-primary">Sign in</button>
            </div>
        </form> -->
    </auth-template>
</template>

<script>
import AuthTemplate from '../templates/auth.vue';
import { mapState } from 'vuex';

export default {
    components: {
        AuthTemplate
    },
    data: function() {
        return {
            shifts: []
        };
    },
    computed: mapState({
        user: 'user',
    }),
    mounted() {
        axios.get('/api/shifts').then((response) => {
            this.shifts = response.data;
        });
    },
    methods: {
    }
}
</script>

<style lang="css" scoped>
</style>
