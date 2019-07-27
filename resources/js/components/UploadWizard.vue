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
              <i class="fa fa-upload mr-1"></i> ou #sélectionne-ton-mp3
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
                  <i class="fa fa-fw fa-spinner fa-spin" v-show="uploadProgress < 100"></i>
                  <i class="fa fa-fw fa-check-circle" v-show="uploadProgress >= 100"></i>
                </div>
                <div class="flex-1">
                  <div class="text-xs mb-1" v-if="files.audio">
                    {{ files.audio.name }}
                    <span class="text-gray-500">({{ uploadProgress }}%)</span>
                  </div>
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
                    onclick="document.getElementById('thumbnailInput').click()"
                    id="thumbnail"
                  >
                    <i class="text-xs fas fa-upload"></i>
                  </div>
                  <input
                    type="file"
                    id="thumbnailInput"
                    v-on:change="onThumbnailInputChange"
                    class="hidden"
                  />
                  <div
                    class="text-red-500 mt-3 text-xs font-bold"
                    v-if="formErrors && formErrors.thumbnail !== undefined"
                  >{{ formErrors.thumbnail[0] }}</div>
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
                    :disabled="formSubmitted"
                  />
                  <div
                    class="text-red-500 mt-3 text-xs font-bold"
                    v-if="formErrors && formErrors.name !== undefined"
                  >{{ formErrors.name[0] }}</div>
                </div>
                <div class="mb-3">
                  <div class="text-xs mb-1">
                    Tags
                    <span class="text-red-500">*</span>
                  </div>
                  <input
                    type="text"
                    class="border-gray-300 border rounded w-full px-2 py-1"
                    @keyup.enter="appendTag()"
                    @keyup.space="appendTag()"
                    v-model="currentTag"
                    :disabled="formSubmitted"
                  />
                  <div
                    class="text-red-500 mt-3 text-xs font-bold"
                    v-if="formErrors && formErrors.tags !== undefined"
                  >{{ formErrors.tags[0] }}</div>

                  <div class="my-3">
                    <div
                      class="text-xs py-1 mb-1 px-2 bg-gray-200 rounded-full hover:bg-gray-300 hover:line-through cursor-pointer mr-1 inline-block"
                      v-for="(tag, i) in sample.tags"
                      :key="i"
                      v-on:click="removeTag(i)"
                    >{{ tag }}</div>
                  </div>
                </div>
                <div v-show="!moreFields" class="text-center text-xs my-3">
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
                      :disabled="formSubmitted"
                    ></textarea>
                    <div
                      class="text-red-500 mt-3 text-xs font-bold"
                      v-if="formErrors && formErrors.description !== undefined"
                    >{{ formErrors.description[0] }}</div>
                  </div>
                </div>
                <div class="text-right">
                  <a
                    href="#"
                    :class="{
                      'bg-gray-300 hover:bg-gray-400': !formSubmitted,
                      'cursor-not-allowed bg-gray-400': formSubmitted,
                      }"
                    class="inline-block px-3 py-1 font-bold rounded-full"
                    v-on:click="submit"
                  >
                    <span v-show="formSubmitted">
                      <i class="fa fa-spinner fa-spin fa-fw"></i>
                    </span>
                    <span v-show="!formSubmitted">
                      <i class="fa fa-upload mr-1"></i> Ajouter
                    </span>
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
import _ from "lodash";

export default {
  data() {
    return {
      dropzone: true,
      moreFields: false,
      sample: {
        id: "",
        thumbnail: "/img/default.png",
        name: "",
        tags: [],
        description: ""
      },
      files: {
        audio: "",
        thumbnail: ""
      },
      fileInput: "",
      uploadProgress: 0,
      uploadError: "",
      formErrors: {},
      formSubmitted: false,
      currentTag: ""
    };
  },
  mounted() {
    let vm = this,
      dropzone = document.getElementById("dropzone"),
      thumbnail = document.getElementById("thumbnail");

    dropzone.addEventListener("dragenter", e => {
      e.preventDefault();

      dropzone.classList.add("border-teal-400");
      dropzone.classList.add("bg-gray-100");
    });
    dropzone.addEventListener("dragover", e => {
      e.preventDefault();

      dropzone.classList.add("border-teal-400");
      dropzone.classList.add("bg-gray-100");
    });
    dropzone.addEventListener("dragleave", e => {
      e.preventDefault();

      dropzone.classList.remove("border-teal-400");
      dropzone.classList.remove("bg-gray-100");
    });
    dropzone.addEventListener("drop", e => {
      e.preventDefault();

      dropzone.classList.remove("border-teal-400");
      dropzone.classList.remove("bg-gray-100");

      dropzone.classList.remove("border-red-500");
      vm.uploadError = null;
      vm.processAudioFile(e.dataTransfer.files[0]);
    });

    thumbnail.addEventListener("dragenter", e => {
      e.preventDefault();

      thumbnail.classList.add("border-teal-400");
      thumbnail.classList.add("opacity-100");
      thumbnail.classList.add("bg-gray-100");
    });
    thumbnail.addEventListener("dragover", e => {
      e.preventDefault();

      thumbnail.classList.add("border-teal-400");
      thumbnail.classList.add("opacity-100");
      thumbnail.classList.add("bg-gray-100");
    });
    thumbnail.addEventListener("dragleave", e => {
      e.preventDefault();

      thumbnail.classList.remove("border-teal-400");
      thumbnail.classList.remove("bg-gray-100");
      thumbnail.classList.remove("opacity-100");
    });
    thumbnail.addEventListener("drop", e => {
      e.preventDefault();

      thumbnail.classList.remove("border-teal-400");
      thumbnail.classList.remove("bg-gray-100");
      thumbnail.classList.remove("opacity-100");

      thumbnail.classList.remove("border-red-500");
      vm.uploadError = null;
      vm.processThumbnailFile(e.dataTransfer.files[0]);
    });
  },
  methods: {
    onAudioInputChange(e) {
      this.processAudioFile(e.target.files[0]);
    },
    processAudioFile(file) {
      let vm = this,
        el = document.getElementById("dropzone");

      if (file.size > uploadMaxSize) {
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
      this.sample.name = file.name.substring(0, file.name.lastIndexOf("."));
      this.dropzone = false;

      this.uploadAudioFile();
    },
    uploadAudioFile() {
      let vm = this,
        formData = new FormData();
      formData.append("audio", this.files.audio);

      axios
        .post("/samples/preflight", formData, {
          headers: { "Content-Type": "multipart/form-data" },
          onUploadProgress: progressEvent => {
            vm.uploadProgress = Math.round(
              (progressEvent.loaded / progressEvent.total) * 100
            );
          }
        })
        .then(
          response => {
            vm.sample.id = response.data.id;
          },
          error => {
            document.getElementById("dropzone").classList.add("border-red-500");
            if (error.response) {
              vm.uploadError = error.response.data.errors.audio[0];
            }
            vm.dropzone = true;
          }
        );
    },
    onThumbnailInputChange(e) {
      this.processThumbnailFile(e.target.files[0]);
    },
    processThumbnailFile(file) {
      let vm = this,
        el = document.getElementById("thumbnail");

      if (file.size > 2 * 1048576) {
        el.classList.add("border-red-500");
        return;
      }

      let extension = file.name
        .substring(file.name.lastIndexOf(".") + 1)
        .toLowerCase();

      if (
        extension != "gif" &&
        extension != "png" &&
        extension != "bmp" &&
        extension != "jpeg" &&
        extension != "jpg"
      ) {
        el.classList.add("border-red-500");
        return;
      }

      this.files.thumbnail = file;

      let reader = new FileReader();
      reader.onload = e => {
        vm.sample.thumbnail = e.target.result;
      };
      reader.readAsDataURL(file);
    },
    appendTag() {
      let safeTag = this.currentTag.trim().replace(/  */gi, "-");
      if (
        safeTag &&
        safeTag != "" &&
        this.sample.tags.indexOf(safeTag) == -1 &&
        safeTag.length < 30 &&
        this.sample.tags.length < 10
      ) {
        this.sample.tags.push(safeTag);
      }

      this.currentTag = "";
    },
    removeTag(i) {
      this.sample.tags.splice(i, 1);
    },
    submit() {
      let vm = this,
        formData = new FormData();

      if (!this.sample.id) return;
      if (this.formSubmitted) return;

      formData.append("id", this.sample.id);
      formData.append("name", this.sample.name);

      this.sample.tags.forEach((tag, i) => {
        formData.append("tags[" + i + "]", tag);
      });

      formData.append("description", this.sample.description);
      formData.append("thumbnail", this.files.thumbnail);

      vm.formSubmitted = true;

      axios
        .post("/samples", formData, {
          headers: { "Content-Type": "multipart/form-data" }
        })
        .then(
          response => {
            window.location = "/samples/" + response.data.id;
          },
          error => {
            vm.formSubmitted = false;
            vm.formErrors = error.response.data.errors;
          }
        );
    }
  }
};
</script>