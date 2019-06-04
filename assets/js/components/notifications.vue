<template lang="html">
    <div class="notification-list">
      <div class="notification" v-for="(notification, index) in notifications">
        <div class="notification__close" @click='deleteNotification(index)'><i class="fas fa-times"></i></div>
        <div class="notification__header">
          <span class="notification__icon">
            <i class="fas fa-exclamation-circle"></i>
          </span>&nbsp;{{notification.type}}
        </div>
        <div class="notification__body">{{notification.text}}</div>
      </div>
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'

export default {
    computed: mapState({
        notifications: state => state.notifications
    }),
    methods: mapActions([
        'deleteNotification'
    ])
}
</script>

<style lang="css" scoped>
</style>
