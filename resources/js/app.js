/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('select2')
let ClipboardJS = require('clipboard/dist/clipboard.min.js')

let $ = require("jquery")

import bsCustomFileInput from 'bs-custom-file-input'
import WaveSurfer from 'wavesurfer.js';

$(document).ready(function () {
    bsCustomFileInput.init()
    var clipboard = new ClipboardJS('[data-clipboard]');

    clipboard.on('success', function (e) {
        $(e.trigger).html("<i class='fas fa-check fa-fw mr-1'></i> Copié");
        e.clearSelection();
    });

    $('[data-wavesurfer]').each(function (k, el) {
        var id = $(el).attr('data-id')
        var src = $(el).attr('data-src')
        var height = $(el).attr('data-height')

        var wavesurfer = WaveSurfer.create({
            container: el,
            waveColor: '#4C5669',
            progressColor: '#4d6e96',
            cursorColor: '#4d6e96',
            height: height,
            normalize: true,
            backend: 'MediaElement'
        })
        wavesurfer.load(src)
        wavesurfer.setVolume(0.7)
        wavesurfer.on('ready', function () {
            $('[data-wavecontrol][data-target=' + id + ']').click(function (e) {
                var $el = $(e.target)
                var control = $el.closest('.btn').attr('data-control')
                switch (control) {
                    case 'play':
                        wavesurfer.play()
                        break
                    case 'pause':
                        wavesurfer.pause()
                        break
                    case 'stop':
                        wavesurfer.stop()
                        break
                    case 'toggle':
                        var $i = ($el.is('i') ? $el : $el.children('i'))
                        if (wavesurfer.isPlaying()) {
                            $i.removeClass().addClass('far fa-play-circle fa-fw')
                        } else {
                            $i.removeClass().addClass('far fa-pause-circle fa-fw')
                        }
                        wavesurfer.playPause()
                        break
                }
            })
        })
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
