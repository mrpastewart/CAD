<template lang="html">
    <login-layout>
        <div class='text-center'>
            <h2>CAD</h2>
            <p>CAD system</p>
        </div>
        <div class='login-form-content' v-if='loaded && user === null'>
            <div class="alert alert-danger mt-3 mb-1" role="alert" v-if="error">
                <div class="text-center text-bold">
                    <p>{{error}}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="email" class="form-control" id="username" v-model="username">
            </div>
            <div class="form-group">
                <label for="username">Password</label>
                <input type="password" class="form-control" id="password" v-model="password">
            </div>
            <button type="button" class="btn-block mt-4 mb-3 btn btn-primary" @click='login'>Login</button>
            <small><a href="">Forgotten password?</a></small>
        </div>
        <div class="spinner" v-if="!loaded"></div>
        </div>
    </login-layout>
</template>

<script>
import LoginLayout from '../templates/login.vue';
import { mapState } from 'vuex';

export default {
    components: {
        LoginLayout
    },
    data: function() {
        return {
            loaded: false,
            username: null,
            password: null,
            error: false
        };
    },
    computed: mapState({
        user: 'user'
    }),
    mounted() {
        this.checkUser();
    },
    methods: {
        login(event) {
            event.preventDefault();
            this.error = false;
            this.loaded = false;
            let self = this;
            axios.post('/auth/login', {
                'username': this.username,
                'password': this.password
            }).catch((error) => {
                self.loaded = true;
                if (error.response.data.error) {
                    this.error = error.response.data.error;
                }
            }).then((response) => {
                self.checkUser();
            });
        },
        checkUser() {
            var self = this;
            this.$store.dispatch('getUser').finally( () => {
                if (self.user) {
                    self.$store.dispatch('getShifts').finally( () => {
                        self.$router.push({name:'home'})
                        self.loaded = true;
                    });
                } else {
                    self.loaded = true;
                }
            });
        }
    }
}
</script>

<style lang="css" scoped>
</style>
