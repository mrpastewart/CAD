<template>
<div class="flex-container">
    <div class="login-main-content">
        <div class="login-form col-sm">
            <div class="alert system-alert text-center" v-if="offline">
                <i class="fas fa-wifi"></i>&nbsp;Unable to establish connection
            </div>
            <slot></slot>
        </div>
    </div>
    <!-- <footer class="footer">
        <span class="text-muted">Kirsty Wright | kirsty.dev</span>
    </footer> -->
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
