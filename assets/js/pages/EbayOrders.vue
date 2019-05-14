<script>
    import order from '../components/Order';
    import tabsettings from '../components/TabSettings.vue';

    export default {
        props: ['notShipped'],

        components: { order, tabsettings }
    }
</script>