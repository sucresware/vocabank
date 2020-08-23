<template>
  <div class="w-full hoverable">
    <div
      class="relative flex flex-no-wrap items-center w-full px-2 py-2 hover:cursor-pointer"
      v-on:click="toggle()"
    >
      <fade-transition :duration="150">
        <div class="absolute top-0 bottom-0 left-0 right-0 px-5" v-show="showWaveform">
          <img :src="sample.waveform_url" class="w-full h-full" style="opacity: 0.30" />
        </div>
      </fade-transition>

      <div class="relative flex-none w-8 h-8">
        <img
          :src="sample.thumbnail_url"
          class="absolute top-0 bottom-0 left-0 right-0 object-cover border-2 border-gray-400 rounded-full"
        />
        <div
          class="absolute top-0 bottom-0 left-0 right-0 flex items-center justify-center text-white border-2 border-gray-400 rounded-full opacity-0 hover:opacity-100"
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
          class="absolute top-0 bottom-0 left-0 right-0 flex items-center justify-center text-white border-2 border-gray-400 rounded-full"
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
      <div class="mx-3 m-w-0">
        <div class="font-bold truncate">{{ sample.name }}</div>
      </div>
    </div>
    <slide-up-down :active="showControls" :duration="200">
      <div
        style="height: 30px;"
        class="relative flex items-center w-full py-8"
        :id="'wavesurfer-' + sample.id"
      ></div>
      <div class="flex flex-wrap items-center px-4 pb-4 text-xs text-center sm:pb-2 sm:text-left">
        <div class="flex-1 mb-2 sm:flex-auto sm:mb-0">
          ajouté {{ sample.presented_date }}
          <template v-if="sample.user">
            — par
            <a v-if="inIframe" :href="'/users/' + sample.user.name" class="link" target="_blank">{{ sample.user.name }}</a>
            <a v-else :href="'/users/' + sample.user.name" class="link">{{ sample.user.name }}</a>
          </template>
        </div>
        <div class="w-full sm:w-auto sm:ml-auto">
          <input type="range" v-model="volume" class="w-24 mr-1 slider">
          <template v-if="inIframe">
            <a
              :href="'/samples/' + sample.id"
              class="btn btn-xs btn-secondary"
              target="_blank"
            >Détails</a>
          </template>
          <template v-else>
            <button
              class="btn btn-xs"
              :class="{
                'btn-primary': sample.liked,
                'btn-secondary': !sample.liked,
              }"
              v-if="sample.liked !== undefined && sample.liked !== null"
              v-on:click="toggleLike()"
              title="Favori"
            >
              <i class="fas fa-heart"></i> <span class="hidden ml-1 md:inline">Favori</span>
            </button>
            <a :href="'/samples/' + sample.id" class="btn btn-xs btn-secondary" title="Détails">
              <i class="fas fa-info-circle"></i>
              <span class="hidden ml-1 md:inline">Détails</span>
            </a>
            <button
              class="btn btn-xs btn-primary"
              v-clipboard:copy="sampleUrl"
              title="Copier le lien"
            >
              <i class="fas fa-copy"></i> <span class="hidden ml-1 md:inline">Copier</span>
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
import axios from "axios";

export default {
  props: ["sample", "iframe"],
  data() {
    return {
      inIframe: false,
      showWaveform: true,
      showControls: false,
      waveSurfer: null,
      isLoading: false,
      isPlaying: false,
      volume: 50,
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
        this.waveSurfer.setVolume(this.volume/100);
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
    },
    toggleLike(){
      this.sample.liked = !this.sample.liked;
      axios.post("/samples/" + this.sample.id + "/like");
    },
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
  },
  watch: {
    volume: function(volume, oldVolume){
      this.waveSurfer.setVolume(volume/100);
    }
  }
};
</script>