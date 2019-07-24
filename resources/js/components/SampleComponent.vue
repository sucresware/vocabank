<template>
  <div>
    <div class="px-2 py-2 w-full flex items-center relative">
      <fade-transition :duration="150">
        <div v-show="showWaveform">
          <div class="absolute px-5 top-0 bottom-0 left-0 right-0">
            <img src="/img/waveform.png" class="w-full h-full" style="opacity: 0.20" />
          </div>
          <div class="absolute px-5 top-0 bottom-0 left-0 right-0">
            <div
              class="w-full h-full"
              style="background: linear-gradient(0deg, #FFFFFF 0%, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 100%);"
            ></div>
          </div>
        </div>
      </fade-transition>

      <div class="relative h-8 w-8">
        <img
          :src="sample.thumbnail_link"
          class="rounded-full border-2 border-gray-400 absolute object-cover top-0 bottom-0 left-0 right-0"
        />
        <div
          class="opacity-0 hover:opacity-100 rounded-full border-2 border-gray-400 absolute top-0 bottom-0 left-0 right-0 text-white flex items-center justify-center"
          style="background: rgba(0, 0, 0, 0.5)"
          v-on:click="toggle()"
        >
          <i
            class="text-xs fas fa-play"
            v-show="!isPlaying && !isLoading"
            :key="'play-' + sample.id"
          ></i>
          <i
            class="text-xs fas fa-pause"
            v-show="isPlaying && !isLoading"
            :key="'pause-' + sample.id"
          ></i>
          <i
            class="text-xs fas fa-circle-notch fa-spin"
            v-show="!isPlaying && isLoading"
            :key="'load-' + sample.id"
          ></i>
        </div>
      </div>
      <div class="z-20 ml-3 truncate font-bold">
        <a :href="'/samples/' + sample.id" class="hover:text-gray-600">{{ sample.name }}</a>
      </div>
      <div class="z-20 ml-auto">
        <i class="fas fa-undo"></i>
        0
      </div>

      <div class="absolute bottom-0 left-0 bg-teal-400" style="height: 3px;"></div>
    </div>
    <!-- <div
      style="height: 30px; border-bottom-width: 1px;"
      class="w-full flex items-center relative border-gray-300 py-8"
      v-show="showControls"
      :id="'wavesurfer-' + sample.id"
    ></div>-->
    <slide-up-down :active="showControls" :duration="200">
      <div
        style="height: 30px; border-bottom-width: 1px;"
        class="w-full flex items-center relative border-gray-300 py-8"
        :id="'wavesurfer-' + sample.id"
      ></div>
    </slide-up-down>
  </div>
</template>

<script>
let $ = require("jquery");
import WaveSurfer from "wavesurfer.js";

export default {
  props: ["sample"],
  data() {
    return {
      showWaveform: true,
      showControls: false,
      waveSurfer: null,
      isLoading: false,
      isPlaying: false
    };
  },
  mounted() {},
  methods: {
    toggle() {
      let vm = this;
      this.showControls = true;
      this.showWaveform = false;

      if (!this.waveSurfer) {
        this.waveSurfer = WaveSurfer.create({
          container: document.getElementById("wavesurfer-" + this.sample.id),
          waveColor: "#a0aec0",
          progressColor: "#00CDCD",
          cursorWidth: "0px",
          height: 30,
          normalize: true,
          backend: "MediaElement",
          responsive: true
        });
        this.waveSurfer.load("/samples/" + this.sample.id + "/listen");
        this.waveSurfer.setVolume(0.7);
        this.isLoading = true;

        this.waveSurfer.on("ready", function() {
          vm.isLoading = false;
          vm.isPlaying = true;
          vm.waveSurfer.play();
        });

        this.waveSurfer.on("play", () => (vm.isPlaying = true));
        this.waveSurfer.on("pause", () => (vm.isPlaying = false));
        this.waveSurfer.on("finish", () => (vm.isPlaying = false));
      } else {
        this.isPlaying = !this.isPlaying;
        this.waveSurfer.playPause();
      }
    }
  }
};
</script>