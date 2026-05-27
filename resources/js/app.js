import Vue from 'vue'
window.Vue = Vue;
require('./bootstrap');

require('./src/plugins');
require('./src/mixin');

import money from 'v-money'
Vue.use(money, {precision: 0, prefix: 'تومان '})

const files = require.context('./components/main/', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const Num2persian = require("num2persian")

window.Vue = require('vue').default;
window.eventBus = new Vue({});
const app = new Vue({
    el: '#app',
});
