<template>
  <div class="hoverable">
    <div
      class="hover:cursor-pointer px-2 py-2 w-full flex items-center relative"
      v-on:click="toggle()"
    >
      <fade-transition :duration="150">
        <div class="absolute px-5 top-0 bottom-0 left-0 right-0" v-show="showWaveform">
          <img :src="sample.waveform_url" class="w-full h-full" style="opacity: 0.30" />
        </div>
      </fade-transition>

      <div class="relative h-8 w-8">
        <img
          :src="sample.thumbnail_url"
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
      <div class="z-20 mx-3 flex-1 truncate font-bold">{{ sample.name }}</div>
      <div class="z-20 ml-auto" v-if="sample.views">
        <i class="fas fa-undo"></i>
        {{ sample.views }}
      </div>
    </div>
    <slide-up-down :active="showControls" :duration="200">
      <div
        style="height: 30px;"
        class="w-full flex items-center relative py-8"
        :id="'wavesurfer-' + sample.id"
      ></div>
      <div class="flex flex-wrap px-4 pb-4 sm:pb-2 text-xs items-center text-center sm:text-left">
        <div class="flex-1 sm:flex-auto mb-2 sm:mb-0">
          ajouté {{ sample.presented_date }}
          <template v-if="sample.user">
            — par
            <a :href="'/users/' + sample.user.id" class="link">{{ sample.user.name }}</a>
          </template>
        </div>
        <div class="w-full sm:w-auto sm:ml-auto">
          <template v-if="inIframe">
            <a
              :href="'/samples/' + sample.id"
              class="btn btn-xs btn-secondary"
              target="_blank"
            >Détails</a>
          </template>
          <template v-else>
            <a :href="'/samples/' + sample.id" class="btn btn-xs btn-secondary">Détails</a>
            <button
              class="btn btn-xs btn-primary"
              v-clipboard:copy="sampleUrl"
              title="Copier le lien"
            >
              <i class="fas fa-copy"></i>
            </button>
          </template>
        </div>
      </div>
    </slide-up-down>
  </div>
</template>

<script>
let $ = require("jquery");
import WaveSurfer from "wavesurfer.js";

export default {
  props: ["sample", "views", "iframe"],
  data() {
    return {
      inIframe: false,
      showWaveform: true,
      showControls: false,
      waveSurfer: null,
      isLoading: false,
      isPlaying: false
    };
  },
  mounted() {
    if (this.iframe) this.inIframe = true;
  },
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
  },
  computed: {
    sampleUrl() {
      return (
        location.protocol +
        "//" +
        location.hostname +
        "/samples/" +
        this.sample.id
      );
    }
  }
};
</script>