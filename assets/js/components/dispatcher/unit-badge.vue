<template lang="html">
    <div class="unit-badge d-inline-flex unit-badge--auto-width">
        <div>
            <div class="unit-badge__primary-text"><span class='state-code__box' v-bind:class="stateStyles">{{unit.status}}</span>&nbsp;{{unit.name}}</div>
            <div class="unit-badge__secondary-text">{{unit.occupant_string}}</div>
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
                <i class="fas fa-angle-double-right"></i>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['unit', 'bus'],
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
        }
    }
}
</script>

<style lang="css" scoped>
</style>
