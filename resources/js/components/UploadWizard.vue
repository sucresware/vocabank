<template>
  <div>
    <div class="bg-white shadow p-10 mb-5">
      <slide-up-down :active="dropzone" :duration="300">
        <div
          class="border-gray-300 border-dashed border-2 w-full flex rounded items-center justify-center text-center"
          style="height: 50vh;"
          id="dropzone"
        >
          <div>
            <div class="font-bold text-xl mb-5">#balance-ton-mp3</div>
            <a
              href="#"
              class="inline-block px-3 py-1 font-bold rounded-full bg-gray-300 hover:bg-gray-400 mb-5"
              onclick="document.getElementById('audioInput').click()"
            >
              <i class="fa fa-upload mr-1"></i> ou #séléctionne-ton-mp3
            </a>
            <input type="file" id="audioInput" v-on:change="onAudioInputChange" class="hidden" />

            <div class="text-red-500 mb-3 font-bold" v-show="uploadError">{{ uploadError }}</div>
            <div class="text-xs">(format mp3 — max. 10 Mo)</div>
          </div>
        </div>
      </slide-up-down>
      <slide-up-down :active="!dropzone" :duration="300">
        <div>
          <div class="mx-auto w-1/2">
            <div class="mb-10">
              <div class="flex items-center">
                <div class="mr-3">
                  <i class="fa fa-fw fa-spinner fa-spin"></i>
                </div>
                <div class="flex-1">
                  <div class="text-xs mb-1" v-if="files.audio">{{ files.audio.name }}</div>
                  <div class="border-gray-300 border rounded w-full h-2">
                    <div class="h-full bg-teal-400" :style="{width: uploadProgress + '%'}"></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="flex w-full justify-center">
              <div>
                <div class="relative h-32 w-32">
                  <img
                    :src="sample.thumbnail"
                    class="w-32 h-32 rounded-full border-2 border-gray-300 absolute object-cover top-0 bottom-0 left-0 right-0"
                  />
                  <div
                    class="opacity-0 hover:opacity-100 rounded-full border-2 border-gray-300 absolute top-0 bottom-0 left-0 right-0 text-white flex items-center justify-center"
                    style="background: rgba(0, 0, 0, 0.5);"
                  >
                    <i class="text-xs fas fa-upload"></i>
                  </div>
                  <div
                    class="rounded-full border-2 border-gray-300 absolute top-0 bottom-0 left-0 right-0 text-white flex items-center justify-center"
                    style="background: rgba(0, 0, 0, 0.5); display: none;"
                  >
                    <i class="text-xs fas fa-play"></i>
                    <i class="text-xs fas fa-pause" style="display: none;"></i>
                    <i class="text-xs fas fa-circle-notch fa-spin" style="display: none;"></i>
                  </div>
                </div>
              </div>
              <div class="ml-5 w-full">
                <div class="mb-3">
                  <div class="text-xs mb-1">
                    Nom
                    <span class="text-red-500">*</span>
                  </div>
                  <input
                    type="text"
                    class="border-gray-300 border rounded w-full px-2 py-1"
                    v-model="sample.name"
                  />
                </div>
                <div class="mb-3">
                  <div class="text-xs mb-1">
                    Tags
                    <span class="text-red-500">*</span>
                  </div>
                  <input
                    type="text"
                    class="border-gray-300 border rounded w-full px-2 py-1 mb-3"
                    @keyup.enter="appendTag()"
                    @keyup.space="appendTag()"
                    v-model="currentTag"
                  />

                  <div class="mb-3">
                    <div
                      class="text-xs py-1 mb-1 px-2 bg-gray-200 rounded-full hover:bg-gray-300 hover:line-through cursor-pointer mr-1 inline-block"
                      v-for="(tag, i) in sample.tags"
                      :key="i"
                      v-on:click="removeTag(i)"
                    >{{ tag }}</div>
                  </div>
                </div>
                <div v-show="!moreFields" class="text-center text-xs mb-3">
                  <div class="bg-gray-400" style="height: 1px;"></div>
                  <div class="-mt-2">
                    <span
                      class="bg-white px-2 text-gray-600 cursor-pointer"
                      v-on:click="moreFields = !moreFields"
                    >
                      plus
                      <i class="fa fa-angle-down"></i>
                    </span>
                  </div>
                </div>
                <div v-show="moreFields">
                  <div class="mb-3">
                    <div class="text-xs mb-1">Description</div>
                    <textarea
                      type="text"
                      class="border-gray-300 border rounded w-full px-2 py-1 h-32"
                      v-model="sample.description"
                    ></textarea>
                  </div>
                </div>
                <div class="text-right">
                  <a
                    href="#"
                    class="inline-block px-3 py-1 font-bold rounded-full bg-gray-300 hover:bg-gray-400"
                  >
                    <i class="fa fa-upload mr-1"></i> Ajouter
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </slide-up-down>
    </div>
  </div>
</template>

<script>
let uploadMaxSize = 10 * 1048576; // 10 Mo
let mimesTypes = ["audio/mpeg", "audio/mp3"];
import axios from "axios";

export default {
  data() {
    return {
      dropzone: true,
      moreFields: false,
      sample: {
        thumbnail: "/img/default.png",
        name: null,
        tags: [],
        description: null
      },
      files: {
        audio: null,
        thumbnail: null
      },
      fileInput: null,
      uploadProgress: 0,
      uploadError: null,
      currentTag: null
    };
  },
  mounted() {
    let vm = this,
      el = document.getElementById("dropzone");

    el.addEventListener("dragenter", e => {
      e.preventDefault();

      el.classList.add("border-teal-400");
      el.classList.add("bg-gray-100");
    });
    el.addEventListener("dragover", e => {
      e.preventDefault();

      el.classList.add("border-teal-400");
      el.classList.add("bg-gray-100");
    });
    el.addEventListener("dragleave", e => {
      e.preventDefault();

      el.classList.remove("border-teal-400");
      el.classList.remove("bg-gray-100");
    });

    el.addEventListener("drop", e => {
      e.preventDefault();

      el.classList.remove("border-teal-400");
      el.classList.remove("bg-gray-100");

      el.classList.remove("border-red-500");
      vm.uploadError = null;
      vm.processAudioFile(e.dataTransfer.files[0]);
    });
  },
  methods: {
    onAudioInputChange(e) {
      this.processAudioFile(e.target.files[0]);
    },
    processAudioFile(uploadedFile) {
      let vm = this,
        el = document.getElementById("dropzone");

      if (uploadedFile.size > uploadMaxSize) {
        el.classList.add("border-red-500");
        this.uploadError = "La taille du fichier doit être inférieure à 10 Mo.";
        return;
      }

      if (mimesTypes.indexOf(uploadedFile.type) === -1) {
        el.classList.add("border-red-500");
        this.uploadError =
          "Le format du fichier est invalide (" + uploadedFile.type + ")";
        return;
      }

      this.files.audio = uploadedFile;
      this.sample.name = uploadedFile.name;
      this.dropzone = false;

      this.uploadAudioFile();
    },
    uploadAudioFile() {
      let formData = new FormData();
      formData.append("audio", this.files.audio);

      axios.put("/samples/preflight", formData, {
        headers: { "Content-Type": "multipart/form-data" },
        onUploadProgress: progressEvent =>
          console.log(progressEvent.loaded, progressEvent.total)
      });
    },
    appendTag() {
      let safeTag = this.currentTag.trim().replace(" ", "-");
      if (safeTag && safeTag != "" && this.sample.tags.indexOf(safeTag) == -1) {
        this.sample.tags.push(safeTag);
      }

      this.currentTag = "";
    },
    removeTag(i) {
      this.sample.tags.splice(i, 1);
    }
  }
};
</script>