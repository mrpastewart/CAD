<template lang="html">
    <div class="dispatcher-panel__block dispatcher-panel__block--secondary">
        <div class="dispatcher-panel__body d-flex">
            <div class="">
                <h4>{{incident.title}}&nbsp;<span class="badge" v-bind:class='{ "badge-danger": (indiaGrade), "badge-success": (!indiaGrade) }'>{{incident.grading}} Grade</span></h4>
                <p>ID: {{incident.id}}</p>
                <p>Interop channel: {{incident.interop}}</p>
                <p>Type:{{incident.type}}</p>
                <p>{{incident.details}}</p>
            </div>
            <div class="ml-auto">
                <div class="btn btn-primary"  @click="open">
                    View Incident
                    <span
                    class="badge badge-light"
                    v-if="notificationCount"
                    >{{notificationCount}}</span>
                </div>
            </div>
        </div>
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
