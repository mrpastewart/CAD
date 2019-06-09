<template lang="html">
    <div class="dispatcher-panel__container col-lg-6">
        <div class="dispatcher-panel__title"
        v-bind:class="{ 'dispatcher-panel__title--selected': (divisionFilter == null) }"
        v-on:click="setDivisionFilter(null)">
            All
        </div>
        <div class="dispatcher-panel__title"
        v-for="division in divisions"
        v-bind:class="{ 'dispatcher-panel__title--selected': (divisionFilter == division.id) }"
        v-on:click="setDivisionFilter(division.id)">
            {{division.shorthand_name}}
        </div>
        <div class="dispatcher-panel__block d-flex">
            <div style='padding:0px 5px;width:50%'>
                <h5>Available:</h5>
                <dispatcher-unit-badge v-if="(unit.division_id == divisionFilter || divisionFilter == null)" v-bind:incident-id='incident.id' type='incident-available' v-bind:unit="unit" v-for="unit in availableUnits"/>
            </div>
            <div style='width:50%;'>
                <h5>Assigned to CAD:</h5>
                <dispatcher-unit-badge v-if="(unit.division_id == divisionFilter || divisionFilter == null)" v-bind:incident-id='incident.id' type='incident-assigned' v-bind:unit="unit" v-for="unit in assignedUnits"/>
                <!--
                <div class="unit-badge d-inline-flex unit-badge--auto-width">
                    <div>
                        <div class="unit-badge__primary-text"><span class='state-code__box'>5</span>&nbsp;CN20</div>
                        <div class="unit-badge__secondary-text"><small>[123] J.Doe</small></div>
                    </div>
                    <div>
                        <div class="unit-badge__role-list">
                            <img class="unit-badge__role-image" src="img/role1/stndt.png" title='' alt="">
                            <img class="unit-badge__role-image" src="img/role2/rmu.png" title="" alt="">
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="ml-auto">
                        <div class="unit-badge__arrow-right">
                            <i class="fas fa-running"></i>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</template>


<script>
import { mapState } from 'vuex'
import DispatcherUnitBadge from './unit-badge.vue';
export default {
    components: {
        DispatcherUnitBadge
    },
    props: ['incident', 'availableUnits', 'assignedUnits', 'type'],
    data: function() {
        return {
            divisionFilter: null
        };
    },
    computed: {
        ...mapState({
            divisions: state => state.divisions,
        })
    },
    methods: {
        setDivisionFilter: function(id) {
            if (id) {
                this.divisionFilter = id;
            } else {
                this.divisionFilter = null;
            }
        }
    }
}
</script>

<style lang="css" scoped>
</style>
