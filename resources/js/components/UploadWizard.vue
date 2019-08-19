<template>
  <div>
    <div class="card p-4 mb-4">
      <slide-up-down :active="step == 1" :duration="300">
        <div
          class="border-gray-400 border-dashed border-2 rounded px-10 py-32 flex justify-center text-center"
          id="dropzone"
        >
          <div>
            <div class="font-bold">#balance-ton-mp3</div>
            <div>Dépose ton fichier dans cette zone</div>
            <div class="mx-auto flex items-center justify-center my-6">
              <hr class="w-full" />
              <div class="px-2">ou</div>
              <hr class="w-full" />
            </div>
            <div class="text-center">
              <button
                class="btn btn-primary"
                onclick="document.getElementById('audioInput').click()"
              >
                <i class="fa fa-upload mr-1"></i> Choisis un fichier
              </button>
            </div>
            <input type="file" id="audioInput" v-on:change="onAudioInputChange" class="hidden" />
            <div class="text-red-500 mt-6 font-bold" v-show="uploadError">{{ uploadError }}</div>
          </div>
        </div>
      </slide-up-down>
      <slide-up-down :active="step == 2" :duration="300">
        <div class="flex items-center w-full">
          <div class="mr-3">
            <i class="fa fa-fw fa-spinner fa-spin" v-show="!processingComplete"></i>
            <i class="fa fa-fw fa-check-circle text-teal-400" v-show="processingComplete"></i>
          </div>
          <div class="flex-1">
            <div class="text-xs mb-1">
              {{ files.audio.name }}
              <span class="text-gray-500">({{ uploadProgress }}%)</span>
            </div>
            <div class="rounded w-full h-2 relative">
              <div class="h-full bg-teal-400" :style="{width: uploadProgress + '%'}"></div>
            </div>
          </div>
        </div>
      </slide-up-down>
    </div>
  </div>
</template>

<script>
let mimesTypes = ["audio/mpeg", "audio/mp3", "audio/wav", "audio/ogg"];
import axios from "axios";
import _ from "lodash";

export default {
  data() {
    return {
      step: 1,
      files: {
        audio: ""
      },
      fileInput: "",
      uploadProgress: 0,
      processingComplete: false,
      uploadError: ""
    };
  },
  mounted() {
    let dropzone = document.getElementById("dropzone");

    dropzone.addEventListener("dragenter", this.dragEnter);
    dropzone.addEventListener("dragover", this.dragEnter);
    dropzone.addEventListener("dragleave", this.dragLeave);
    dropzone.addEventListener("drop", this.drop);
  },
  methods: {
    dragEnter(e) {
      e.preventDefault();
      dropzone.classList.add("border-teal-400");
    },
    dragLeave(e) {
      e.preventDefault();
      dropzone.classList.remove("border-teal-400");
    },
    drop(e) {
      e.preventDefault();
      dropzone.classList.remove("border-teal-400");
      dropzone.classList.remove("border-red-500");
      this.uploadError = null;
      this.processAudioFile(e.dataTransfer.files[0]);
    },
    onAudioInputChange(e) {
      e.preventDefault();
      this.processAudioFile(e.target.files[0]);
    },
    processAudioFile(file) {
      let el = document.getElementById("dropzone");

      if (file.size > 10 * 1048576) {
        // 10 Mo
        el.classList.add("border-red-500");
        this.uploadError = "La taille du fichier doit être inférieure à 10 Mo.";
        return;
      }

      if (mimesTypes.indexOf(file.type) === -1) {
        el.classList.add("border-red-500");
        this.uploadError =
          "Le format du fichier est invalide (" + file.type + ")";
        return;
      }

      this.files.audio = file;
      this.step = 2;

      this.uploadAudioFile();
    },
    uploadAudioFile() {
      let formData = new FormData();
      formData.append("audio", this.files.audio);

      axios
        .post("/samples/preflight", formData, {
          headers: { "Content-Type": "multipart/form-data" },
          onUploadProgress: progressEvent => {
            this.uploadProgress = Math.round(
              (progressEvent.loaded / progressEvent.total) * 100
            );
          }
        })
        .then(
          response => {
            this.processingComplete = true;
            window.location = "/samples/" + response.data.id + "/edit";
          },
          error => {
            document.getElementById("dropzone").classList.add("border-red-500");
            this.step = 1;
            if (error.response)
              this.uploadError = error.response.data.errors.audio[0];
          }
        );
    }
  }
};
</script>