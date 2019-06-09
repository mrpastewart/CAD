<template>
<div class="mdt-container">
    <div class="login-form mdt-panel">
        <div class="alert system-alert text-center" v-if="offline">
            <i class="fas fa-wifi"></i>&nbsp;Unable to establish connection
        </div>
        <slot></slot>
    </div>
</div>
</template>

<script>
import {
    mapState
} from 'vuex'
export default {
    components: {
    },
    computed: {
        ...mapState({
            offline: 'offline',
        })
    }
}
</script>

<style scoped>
</style>
