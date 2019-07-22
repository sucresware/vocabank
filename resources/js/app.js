/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('select2')
let ClipboardJS = require('clipboard/dist/clipboard.min.js')
let $ = require("jquery")
let waveSurfers = [];

import bsCustomFileInput from 'bs-custom-file-input'
import WaveSurfer from 'wavesurfer.js';

$(document).ready(function () {
    bsCustomFileInput.init()
    let clipboard = new ClipboardJS('[data-clipboard]');

    clipboard.on('success', function (e) {
        $(e.trigger).html("<i class='fas fa-check fa-fw mr-1'></i> Copié");
        e.clearSelection();
    });

    $('[data-wavesurfer]').each(function (k, el) {
        let id = $(el).attr('data-id')
        let src = $(el).attr('data-src')
        let height = $(el).attr('data-height')

        let wavesurfer = WaveSurfer.create({
            container: el,
            waveColor: '#a0aec0',
            progressColor: '#4a5568',
            cursorWidth: '0px',
            height: height,
            normalize: true,
            backend: 'MediaElement'
        })
        wavesurfer.load(src)
        wavesurfer.setVolume(0.7)
        wavesurfer.on('ready', function () {
            $('[data-wavecontrol][data-target=' + id + ']').click(function (e) {
                let $el = $(e.target),
                    control = $el.closest('a[data-wavecontrol]').attr('data-control');

                switch (control) {
                    case 'play':
                        stopAll();
                        wavesurfer.play()

                        break
                    case 'pause':
                        wavesurfer.pause()
                        break
                }
            })
        })
        wavesurfer.on('play', function () {
            let $playControl = $('a[data-wavecontrol][data-control="play"][data-target=' + id + ']'),
                $pauseControl = $('a[data-wavecontrol][data-control="pause"][data-target=' + id + ']');
            $playControl.addClass('hidden')
            $pauseControl.removeClass('hidden')
        })

        wavesurfer.on('pause', function () {
            let $playControl = $('a[data-wavecontrol][data-control="play"][data-target=' + id + ']'),
                $pauseControl = $('a[data-wavecontrol][data-control="pause"][data-target=' + id + ']');
            $playControl.removeClass('hidden')
            $pauseControl.addClass('hidden')
        })

        wavesurfer.on('finish', function () {
            let $playControl = $('a[data-wavecontrol][data-control="play"][data-target=' + id + ']'),
                $pauseControl = $('a[data-wavecontrol][data-control="pause"][data-target=' + id + ']');
            $playControl.removeClass('hidden')
            $pauseControl.addClass('hidden')
        })

        waveSurfers.push(wavesurfer);
    })

    // $('.select2').each(function (k, el) {
    //     $(el).select2({
    //         theme: 'bootstrap4',
    //     })
    // })

    // $('.select2-tags').each(function (k, el) {
    //     $(el).select2({
    //         // theme: 'bootstrap4',
    //         tags: true
    //     })
    // })
})

function stopAll() {
    waveSurfers.forEach((wavesurfer) => {
        wavesurfer.pause()
    })
}
