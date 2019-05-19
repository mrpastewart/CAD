<template lang="html">
    <div class="notification-list"></div>
</template>

<script>
import mapState from 'vuex'

export default {
    data: function() {
        ...mapState({
            notifications: state => state.notifications
        })
    }
}
</script>

<style lang="css" scoped>
</style>
