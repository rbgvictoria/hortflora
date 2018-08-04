<template>
    <div>
        <banner></banner>
        <router-view></router-view>
        <page-footer></page-footer>
    </div>
</template>

<script>
    import Banner from './Banner.vue';
    import PageFooter from './Footer.vue';

    export default {
        components: {
            Banner,
            PageFooter
        }
    }
</script>
