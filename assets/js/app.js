const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

require('../css/app.scss');

import Vue from 'vue';
import axios from 'axios';

window.Vue = require('vue');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

Vue.component('refresh-token', require('./components/RefreshToken.vue').default);

Vue.component('ebay-items', require('./pages/EbayItems.vue').default);

new Vue({
    el: '#app'
});