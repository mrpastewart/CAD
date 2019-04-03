<template lang="html">
    <div class="dispatcher-panel__container col-lg-6">
        <div class="dispatcher-panel__title dispatcher-panel__title--selected">
            All
        </div>
        <div class="dispatcher-panel__title">
            TP
        </div>
        <div class="dispatcher-panel__title">
            CS
        </div>
        <div class="dispatcher-panel__title">
            Traffic
        </div>
        <div class="dispatcher-panel__title">
            Firearms
        </div>
        <div class="dispatcher-panel__title">
            RMU
        </div>
        <div class="dispatcher-panel__block d-flex">
            <div style='padding:0px 5px;width:50%'>
                <h5>Available:</h5>
                <dispatcher-unit-badge v-bind:incident-id='incident.id' type='incident-available' v-bind:unit="unit" :bus="bus" v-for="unit in availableUnits"/>
            </div>
            <div style='width:50%;'>
                <h5>Assigned to CAD:</h5>
                <dispatcher-unit-badge v-bind:incident-id='incident.id' type='incident-assigned' v-bind:unit="unit" :bus="bus" v-for="unit in assignedUnits"/>
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
export default {
    props: ['inputIncident', 'bus', 'availableUnits', 'assignedUnits', 'type'],
    data: function() {
        return {
            'incident': this.inputIncident
        };
    }
}
</script>

<style lang="css" scoped>
</style>
