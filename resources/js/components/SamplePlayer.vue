<template>
  <div class="flex items-center">
    <div class="mx-3">
      <div class="relative h-10 w-10" v-on:click="toggle()">
        <div
          class="rounded-full border-2 border-gray-400 absolute top-0 bottom-0 left-0 right-0 text-gray-600 flex items-center justify-center"
        >
          <i
            class="text-xs fas fa-play"
            v-show="!isPlaying && !isLoading"
            :key="'play-' + sample.id"
          ></i>
        </div>
        <div
          class="rounded-full border-2 border-gray-400 absolute top-0 bottom-0 left-0 right-0 text-gray-600 flex items-center justify-center"
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
    </div>
    <div
      style="height: 30px;"
      class="w-full flex items-center relative py-8"
      :id="'wavesurfer-' + sample.id"
    ></div>
  </div>
</template>

<script>
let $ = require("jquery");
import WaveSurfer from "wavesurfer.js";

export default {
  props: ["sample"],
  data() {
    return {
      waveSurfer: null,
      isLoading: false,
      isPlaying: false
    };
  },
  mounted() {
    let vm = this;

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
  },
  methods: {
    toggle() {
      this.isPlaying = !this.isPlaying;
      this.waveSurfer.playPause();
    }
  }
};
</script>