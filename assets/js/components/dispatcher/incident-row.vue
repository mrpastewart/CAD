<template lang="html">
    <div class="dispatcher-panel__block dispatcher-panel__block--secondary">
        <div class="dispatcher-panel__body d-flex" @click="open">
            <div class="">
                <h4>{{incident.title}}&nbsp;<span class="badge" v-bind:class='{ "badge-danger": (indiaGrade), "badge-success": (!indiaGrade) }'>{{incident.grading}} Grade</span></h4>
                <div>
                    #{{incident.id}}&nbsp;&nbsp;
                    <i class="fas fa-users"></i>&nbsp;{{incident.units.length}}&nbsp;&nbsp;
                    <i class="fas fa-comments"></i>&nbsp;{{incident.logs.length}}
                </div>
                <p>Interop channel: {{incident.interop}}</p>
                <p>Type:{{incident.type}}</p>
            </div>
            <div class="ml-auto">
                <div class="btn btn-primary" v-if="notificationCount">
                    <span class="badge badge-light" >{{notificationCount}}</span>
                </div>
            </div>
        </div>
        <hr>
    </div>
</template>

<script>
export default {
    props: ['incident'],
    data() {
        return {
            'indiaGrade': (this.incident.grading == 'I'),
            'notificationCount': 0
        };
    },
    mounted () {
        this.notificationCount = 0;
    },
    methods: {
        open() {
            this.$store.dispatch('setIncident', { id: this.incident.id});
        }
    }
}
</script>

<style lang="css" scoped>
</style>
