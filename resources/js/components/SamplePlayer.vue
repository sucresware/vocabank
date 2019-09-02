<template>
  <div class="flex items-center">
    <div class="mx-3">
      <div class="relative h-10 w-10" v-on:click="toggle()">
        <div
          class="rounded-full border-2 border-gray-400 absolute top-0 bottom-0 left-0 right-0 flex items-center justify-center"
        >
          <i
            class="text-xs fas fa-play"
            v-show="!isPlaying && !isLoading"
            :key="'play-' + sample.id"
          ></i>
        </div>
        <div
          class="rounded-full border-2 border-gray-400 absolute top-0 bottom-0 left-0 right-0 flex items-center justify-center"
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
    <div style="height: 30px;" class="w-full flex items-center relative py-8 mr-3" ref="wavesurfer"></div>
    <input type="range" class="slider mr-3" :class="{ hidden: !showVolume }" v-model="volume">
    <div class="mr-3">
      <button class="btn btn-xs btn-secondary" v-on:click="toggleVolumeControl">
        <i class="fas fa-volume-up inline"></i>
      </button>
    </div>
  </div>
</template>

<script>
let $ = require("jquery");
import WaveSurfer from "wavesurfer.js";

export default {
  props: ["sample", "autoload", "autoplay"],
  data() {
    return {
      waveSurfer: null,
      isLoading: true,
      isPlaying: false,
      hasAutoLoad: false,
      hasAutoPlay: false,
      showVolume: false,
      volume: 50,
    };
  },
  mounted() {
    if (typeof this.autoload == "undefined") this.hasAutoLoad = true;
    if (typeof this.autoplay == "undefined") this.hasAutoPlay = true;

    if (this.hasAutoLoad) this.load();
  },
  methods: {
    load() {
      let vm = this;

      this.waveSurfer = WaveSurfer.create({
        container: this.$refs.wavesurfer,
        waveColor: "#a0aec0",
        progressColor: "#00CDCD",
        cursorWidth: "0px",
        height: 30,
        normalize: true,
        backend: "MediaElement",
        responsive: true
      });
      this.waveSurfer.load("/samples/" + this.sample.id + "/listen");
      this.waveSurfer.setVolume(this.volume/100);
      this.isLoading = true;

      this.waveSurfer.on("ready", function() {
        vm.isLoading = false;
        if (vm.hasAutoPlay) {
          vm.isPlaying = true;
          vm.waveSurfer.play();
        }
      });

      this.waveSurfer.on("play", () => (vm.isPlaying = true));
      this.waveSurfer.on("pause", () => (vm.isPlaying = false));
      this.waveSurfer.on("finish", () => (vm.isPlaying = false));
    },
    toggle() {
      this.isPlaying = !this.isPlaying;
      this.waveSurfer.playPause();
    },
    toggleVolumeControl(){
      this.showVolume = !this.showVolume;
    }
  },
  watch: {
    volume: function(volume, oldVolume){
      this.waveSurfer.setVolume(volume/100);
    }
  }
};
</script>