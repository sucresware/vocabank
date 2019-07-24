require('./bootstrap');
require('select2')

let $ = require("jquery")
let ClipboardJS = require('clipboard/dist/clipboard.min.js')

window.Vue = require('vue');

const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

import SlideUpDown from 'vue-slide-up-down'
Vue.component('slide-up-down', SlideUpDown)

import Transitions from 'vue2-transitions'
Vue.use(Transitions)

const app = new Vue({
    el: '#app',
});
