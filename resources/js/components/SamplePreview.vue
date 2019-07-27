<template>
  <div class="hover:bg-gray-100">
    <div
      class="hover:cursor-pointer px-2 py-2 w-full flex items-center relative"
      v-on:click="toggle()"
    >
      <fade-transition :duration="150">
        <div class="absolute px-5 top-0 bottom-0 left-0 right-0" v-show="showWaveform">
          <img :src="'/storage/' + sample.waveform" class="w-full h-full" style="opacity: 0.20" />
        </div>
      </fade-transition>

      <div class="relative h-8 w-8">
        <img
          :src="sample.thumbnail ? '/storage/' + sample.thumbnail : '/img/default.png'"
          class="rounded-full border-2 border-gray-400 absolute object-cover top-0 bottom-0 left-0 right-0"
        />
        <div
          class="opacity-0 hover:opacity-100 rounded-full border-2 border-gray-400 absolute top-0 bottom-0 left-0 right-0 text-white flex items-center justify-center"
          style="background: rgba(0, 0, 0, 0.5)"
          v-show="!showControls"
        >
          <i
            class="text-xs fas fa-play"
            v-show="!isPlaying && !isLoading"
            :key="'play-' + sample.id"
          ></i>
        </div>
        <div
          class="rounded-full border-2 border-gray-400 absolute top-0 bottom-0 left-0 right-0 text-white flex items-center justify-center"
          style="background: rgba(0, 0, 0, 0.5)"
          v-show="showControls"
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
        <!-- <a :href="'/samples/' + sample.id" class="hover:text-gray-600"></a> -->
        {{ sample.name }}
      </div>
      <div class="z-20 ml-auto">
        <i class="fas fa-undo"></i>
        {{ views }}
      </div>

      <div class="absolute bottom-0 left-0 bg-teal-500" style="height: 3px;"></div>
    </div>
    <!-- <div
      style="height: 30px; border-bottom-width: 1px;"
      class="w-full flex items-center relative border-gray-300 py-8"
      v-show="showControls"
      :id="'wavesurfer-' + sample.id"
    ></div>-->
    <slide-up-down :active="showControls" :duration="200">
      <div style="border-bottom-width: 1px;" class="border-gray-300">
        <div
          style="height: 30px;"
          class="w-full flex items-center relative py-8"
          :id="'wavesurfer-' + sample.id"
        ></div>
        <div class="flex flex-wrap px-3 mb-3 items-end">
          <div class="flex-auto">
            il y a 3 jours par
            <a href="#" class="text-gray-900 hover:text-gray-600">YvonEnbaver</a>
          </div>
          <div class="ml-auto">
            <a
              :href="'/samples/' + sample.id"
              class="inline-block mr-1 px-3 py-1 font-bold rounded-full bg-gray-300 hover:bg-gray-400 text-xs"
            >Détails</a>
            <a
              :href="'/samples/' + sample.id"
              class="inline-block mr-1 px-3 py-1 font-bold rounded-full bg-gray-300 hover:bg-gray-400 text-xs"
            >
              <i class="fas fa-copy"></i>
            </a>
          </div>
        </div>
      </div>
    </slide-up-down>
  </div>
</template>

<script>
let $ = require("jquery");
import WaveSurfer from "wavesurfer.js";

export default {
  props: ["sample", "views"],
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
      this.showControls = !this.showControls;
      this.showWaveform = !this.showWaveform;

      if (this.showWaveform && !this.isPlaying) return;

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