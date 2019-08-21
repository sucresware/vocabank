require('./bootstrap');
let ClipboardJS = require('clipboard/dist/clipboard.min.js')
let axios = require('axios');

import {
    Howl,
    Howler
} from 'howler';

/** Vue */

window.Vue = require('vue');

const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

import SlideUpDown from 'vue-slide-up-down'
import InfiniteLoading from 'vue-infinite-loading';
import Transitions from 'vue2-transitions'
import VueClipboard from 'vue-clipboard2'

Vue.component('slide-up-down', SlideUpDown)
Vue.use(InfiniteLoading)
Vue.use(Transitions)
Vue.use(VueClipboard)

const app = new Vue({
    el: '#app',
});

/** Navbar */
let navToggle = document.getElementById('nav-toggle');
if (navToggle) {
    navToggle.onclick = () => document.getElementById("nav-content").classList.toggle("hidden");
}

/** ClipboardJS */
let dataClipboard = document.querySelector('[data-clipboard]');

if (dataClipboard) new ClipboardJS(dataClipboard);

/** Onche Light Switcher */
let lightSwitch = document.getElementById('lightSwitch');

if (lightSwitch) {
    let lightTogglerPlayer = new Howl({
        src: '/audio/tink.mp3',
        volume: .6,
        html5: true,
        onloaderror: () => {
            console.warn('Could not load light toggler sound.')
        }
    });

    lightSwitch.onclick = function(event) {
        let bodyClasses = document.querySelector('body').classList;

        if (bodyClasses.contains('theme-legacy')) {
            bodyClasses.add('theme-vocabank');
            bodyClasses.remove('theme-legacy');
        } else {
            bodyClasses.add('theme-legacy');
            bodyClasses.remove('theme-vocabank');
        }

        axios.get("/light-toggler");
        lightTogglerPlayer.play();
    }
}