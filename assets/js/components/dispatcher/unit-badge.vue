<template lang="html">
    <div class="unit-badge d-inline-flex">
        <div>
            <div class="unit-badge__primary-text"><span class='state-code__box' v-bind:class="stateStyles">{{unit.status}}</span>&nbsp;{{unit.name}}</div>
            <div class="unit-badge__secondary-text">{{unit.occupant_string}}</div>
        </div>
        <div class="ml-auto d-flex">
            <div class="unit-badge__role-list">
                <img class="unit-badge__role-image" src="/img/role1/stndt.png" title='' alt="">
                <div class="clear"></div>
            </div>
            <div class="unit-badge__button-container">
                <i class="fas fa-pen"></i>
                <i class="" v-bind:class="actionStyles" @click='action'></i>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['unit', 'type', 'incidentId'],
    data: function () {
        return {
        };
    },
    computed: {
        stateStyles: function () {
            let object = {
                'state-zero-flash': (this.unit.status == 0)
            }
            object['state-code__box--'+this.unit.status] = true;
            return object;
        },
        actionStyles: function() {
            let object = {}
            if (this.type == 'incident-available') {
                object['fas fa-angle-double-right'] = true;
            } else if (this.type == 'incident-assigned') {
                object['fas fa-times'] = true;
            } else {
                object['fas fa-eye'] = true;
            }
            return object;
        }
    },
    mounted: function() {

    },
    methods: {
        action: function() {
            let self = this;
            if (this.type == 'incident-available') {
                // assign unit to incident
                axios.post('/api/incidents/'+this.incidentId+'/units/'+this.unit.id).then(function() {
                    self.$store.dispatch('updateDispatcher');
                });
            } else if (this.type == 'incident-assigned') {
                // unassign unit to incident
                axios.delete('/api/incidents/'+this.incidentId+'/units/'+this.unit.id).then(function() {
                    self.$store.dispatch('updateDispatcher');
                });
            }


        }
    }
}
</script>

<style lang="scss">

.unit-badge__button-container {
  margin-left: 5px;
  display:flex;
  align-items:center;
  font-size:20px;
  & > i {
    padding:0px 4px;
  }
}
</style>
