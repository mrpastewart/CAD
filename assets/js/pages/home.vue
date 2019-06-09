<template lang="html">
    <auth-template>
        <shift-signup v-for="shift in shifts" v-bind:shift="shift"></shift-signup>
    </auth-template>
</template>

<script>
import AuthTemplate from '../templates/auth.vue';
import ShiftSignup from '../components/shift-signup.vue';
import { mapState } from 'vuex';

export default {
    components: {
        AuthTemplate,
        ShiftSignup
    },
    computed: mapState({
        user: 'user',
        shifts: 'shifts'
    }),
    mounted() {
    },
    methods: {
    }
}
</script>

<style lang="css" scoped>
</style>
