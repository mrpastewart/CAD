<template lang="html">
    <auth-template>
        <div class='text-center' v-if='user'>
            <h2>CAD - {{user.name}}</h2>
            <button class='mt-3 btn btn-block btn-lg btn-primary' type="button" name="button" v-if="user.unit_id != null" @click='$router.push({name:"mdt"})'>Open MDT</button>
            <button class='mt-3 btn btn-block btn-lg btn-primary' type="button" name="button" v-if="user.unit_id != null" @click="$router.push({name:'dispatcher',params:{'shiftId':shift.id}})" v-for="shift in shifts">{{shift.name}} dispatcher</button>
            <button class='mt-3 btn btn-block btn-lg btn-danger' type="button" name="button" v-if="user.unit_id != null">Logout</button>
        </div>
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
