const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

require('../css/app.scss');

import Vue from 'vue';
import axios from 'axios';

window.Vue = require('vue');

window.axios = require('axios');

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');
Vue.component('ebay-items', require('./pages/EbayItems.vue').default);
Vue.component('edit-item', require('./pages/EditItem.vue').default);
Vue.component('side-nav', require('./components/SideNav.vue').default);
Vue.component('ebay-orders', require('./pages/EbayOrders.vue').default);


new Vue({
    el: '#app'
});