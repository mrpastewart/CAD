<template>
    <div class="dispatch-container" v-bind:class="{'dispatch-container--small': this.$route.name == 'mdt'}" v-if="user">
        <div class="dispatch-container__heading" v-if="(user && user.unit_id && this.$route.name != 'mdt') || offline">
            <div class="alert alert-primary text-center" v-if="user && user.unit_id">
                <router-link to="/mdt"><i class="fas fa-clipboard"></i>&nbsp;Open MDT for current shift</router-link>
            </div>
            <div v-if="offline" class="alert system-alert text-center"><i class="fas fa-wifi"></i>&nbsp;Unable to establish connection </div>
        </div>
        <div class='dispatch-container__container'>
            <div class="menu">
                <div class="menu__item">
                    <div class="spinner spinner--menu" v-bind:class="{'spinner--hidden': !loading}"></div>
                </div>
                <slot name="menu">
                    <router-link to="/home">
                        <div class="menu__item">
                            <i class="fas fa-home"></i>
                        </div>
                    </router-link>
                    <router-link to="/dispatcher">
                        <div class="menu__item">
                            <i class="fas fa-headset"></i>
                        </div>
                    </router-link>
                    <div class="menu__item">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="menu__item">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="menu__item">
                        <i class="fas fa-sign-out-alt"></i>
                    </div>
                </slot>
            </div>
            <div class="dispatch-container__content">
                <slot></slot>
            </div>
            <div class="dispatch-container__content" v-if="false">
                <div class="spinner spinner--fullscreen"></div>
            </div>
        </div>
    </div>
</template>

<script>
import {
    mapState
} from 'vuex';

export default {
    components: {},
    computed: mapState({
        user: 'user',
        offline: 'offline',
        loading: 'loading'
    }),
    mounted: function()
    {
        this.$store.dispatch('getUser');
    }
}
</script>

<style lang="scss">
$color1: rgba(79, 91, 109, 1);
$color2: rgba(56, 67, 89, 1);
$color3: rgba(37, 48, 49, 1);
$color4: rgba(255, 255, 255, 1);
$color5: rgba(41, 120, 160, 1);

$breakpoints: (
    xs: 575.98px, // extra small
    sm: 767.98px,
    // small
    md: 991.98px,
    // middle
    lg: 1199.98px
    // large
);

@mixin breakpoint($bp, $rule: max-width) {
    @media screen and (#{$rule}: map-get($breakpoints, $bp)) {
        @content;
    }
}

body,
html {
    padding: 0;
    margin: 0;
    width: 100%;
    height: 100%;
    background-color: #4f5b6d;
    color: #fff;
}

div {
    box-sizing: border-box;
}

.dispatch-container {
    background-color: #384359;
    &--small {
        max-width:550px;
        margin:0 auto;
    }
    &__container {
        display: flex;
        flex-wrap: nowrap;
        @include breakpoint(sm) {
            flex-wrap: wrap;
        }
    }
    &__heading {
        width: 100%;
        padding: 5px;
        & > .alert {
            margin-bottom:0;
        }
    }
    &__content {
        margin: 10px;
        display: flex;
        flex-wrap: wrap;
        width: 100%;
        @include breakpoint(sm) {
            padding: 10px;
            justify-content:center;
        }
    }
}

.menu {
    padding: 0;
    margin: 10px 0;
    border-right: 1px solid #253031;
    a {
        color:#fff;
    }
    &--horizontal {
        border-right: none;
        width: 100%;
        border-bottom: 1px solid #253031;
        display: flex;
        align-items: center;
        order: 100;
        margin: -10px 0 0 -10px;
    }
    @include breakpoint(sm) {
        border-right: none;
        width: 100%;
        border-top: 1px solid #253031;
        display: flex;
        align-items: center;
        justify-content: center;
        order: 100;
        margin: 0;
    }
    &__item:last-child {
        border-bottom: none;
        @include breakpoint(sm) {
            border-right: none;
        }
    }
    &--horizontal &__item:last-child {
        border-right: none;
    }
    &--horizontal &__item {
        border-bottom: none;
        padding-right:0px;
        // border-right: 1px solid #253031;
        width: auto;
    }
    &__item {
        &--small {
            font-size: 1rem;
        }
        font-size: 2rem;
        text-align: center;
        width: 60px;
        line-height: 40px;
        padding: 5px 10px;
        border-bottom: 1px solid #253031;
        @include breakpoint(sm) {
            border-bottom: none;
            border-right: 1px solid #253031;
        }
        .router-link-active > &,
        &:hover {
            color: #2978a0;
            cursor: pointer;
        }
    }
}

/**
SPINNER
**/

.spinner {
    width: 50px;
    height: 50px;
    position: relative;

    &--fullscreen {
        margin: 0 auto;
        align-self: center;
    }

    &--menu.spinner {
        width:20px;
        height:20px;
        margin:0 auto;
        padding:0;
        margin-bottom:10px;
        &--hidden::before {
            visibility:hidden;
        }
        &::before {
            width:20px;
            height:20px;
        }
        &::after {
            display:none;
            width:20px;
            height:20px;
        }
    }
    &::before {
        width: 50px;
        height: 50px;
        position: absolute;
        top: 0;
        left: 0;
        display: block;
        content: "";
        border-radius: 50%;
        border: 1px solid transparent;
        border-left: 1px solid #ccc;
        border-bottom: none;
        animation-name: spinner--spin;
        animation-duration: 1000ms;
        animation-iteration-count: infinite;
        animation-timing-function: linear;
        position: relative;
    }

    &::after {
        content: "";
        display: block;
        border-radius: 50%;
        border: 1px solid transparent;
        border-right: 1px solid #ccc;
        border-bottom: none;
        width: 40px;
        height: 40px;
        top: 5px;
        left: 5px;
        position: absolute;
        animation-name: spinner--spin;
        animation-duration: 1000ms;
        animation-iteration-count: infinite;
        animation-timing-function: linear;
        animation-direction: reverse;
    }
}

@keyframes spinner--spin {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}

.system-alert {
    margin: 10px;
    background-color: rgb(255, 18, 39);
}
</style>
