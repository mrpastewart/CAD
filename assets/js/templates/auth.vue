<template>
    <div class="mdt-container">
        <div class="mdt-panel">
            <div class="login-form">
                <div class="alert system-alert text-center" v-if="offline">
                    <i class="fas fa-wifi"></i>&nbsp;Unable to establish connection
                </div>
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    components: {
    },
    computed: mapState({
        user: 'user',
        offline: 'offline',
    }),
}
</script>

<style scoped>
</style>
