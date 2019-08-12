require('./bootstrap');
require('select2')

let $ = require("jquery")
let ClipboardJS = require('clipboard/dist/clipboard.min.js')
let axios = require('axios');
import {
    Howl,
    Howler
} from 'howler';


window.Vue = require('vue');

const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

import SlideUpDown from 'vue-slide-up-down'
Vue.component('slide-up-down', SlideUpDown)

import InfiniteLoading from 'vue-infinite-loading';
Vue.use(InfiniteLoading)

import Transitions from 'vue2-transitions'
Vue.use(Transitions)

const app = new Vue({
    el: '#app',
});

let lightTogglerPlayer = new Howl({
    src: '/audio/tink.mp3',
    volume: .6,
    html5: true,
    onloaderror: () => {
        console.warn('Could not load light toggler sound.')
    }
});

window.onbeforeunload = function (event) {
    let logo = document.getElementById("logo-replay");
    logo.classList.add('spinner')
}

document.getElementById('lightSwitch').onclick = function(event) {
    let bodyClasses = document.querySelector('body').classList;

    lightTogglerPlayer.play();
    axios.get("/light-toggler");

    if (bodyClasses.contains('theme-legacy')) {
        bodyClasses.remove('theme-legacy');
        bodyClasses.add('theme-vocabank');
    } else {
        bodyClasses.remove('theme-vocabank');
        bodyClasses.add('theme-legacy');
    }
}