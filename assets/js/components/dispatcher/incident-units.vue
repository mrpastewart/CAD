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
            <div style='padding:0px 5px;'>
                <h5>Available:</h5>
                <div class="unit-badge d-inline-flex unit-badge--auto-width">
                    <div>
                        <div class="unit-badge__primary-text"><span class='state-code__box'>2</span>&nbsp;CN20</div>
                        <div class="unit-badge__secondary-text">[910] P.Addison</div>
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
                <div class="unit-badge d-inline-flex unit-badge--auto-width">
                    <div>
                        <div class="unit-badge__primary-text"><span class='state-code__box'>2</span>&nbsp;CN20</div>
                        <div class="unit-badge__secondary-text">[910] P.Addison</div>
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
            </div>
            <div>
                <h5>Assigned to CAD:</h5>
                <div class="unit-badge d-inline-flex unit-badge--auto-width">
                    <div>
                        <div class="unit-badge__primary-text"><span class='state-code__box state-code__box--0 state-zero-flash'>0</span>&nbsp;CN20</div>
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
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
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
                </div>
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
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    props: ['inputIncident', 'bus'],
    data: function() {
        return {
            'incident': this.inputIncident
        };
    },
    methods: {}
}
</script>

<style lang="css" scoped>
</style>
