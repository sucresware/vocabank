<template>
  <div class="flex w-full justify-center">
    <div class="w-1/2">
      <div class="bg-white shadow mb-5">
        <slide-up-down :active="step == 1" :duration="300">
          <div
            class="bg-gray-800 text-gray-200 text-white h-64 w-full flex items-center justify-center text-center"
          >
            <div class="border-gray-400 border-dashed border-2 rounded px-10 py-5" id="dropzone">
              <span class="text-center font-bold">#balance-ton-mp3</span>
            </div>
          </div>
          <div class="p-10 text-center">
            <a
              href="#"
              class="inline-block px-3 py-1 font-bold rounded-full bg-gray-300 hover:bg-gray-400 mb-5"
              onclick="document.getElementById('audioInput').click()"
            >
              <i class="fa fa-upload mr-1"></i> Choisir un fichier
            </a>
            <input type="file" id="audioInput" v-on:change="onAudioInputChange" class="hidden" />
            <div class="text-red-500 mb-3 font-bold" v-show="uploadError">{{ uploadError }}</div>

            <div class="text-center mb-6 mt-2 px-32">
              <div class="bg-gray-300" style="height: 1px;"></div>
              <div class="-mt-3">
                <span class="bg-white px-2">ou</span>
              </div>
            </div>

            <input
              type="text"
              placeholder="Coller un lien YouTube"
              class="bg-gray-200 rounded-full px-4 py-1 text-center"
              :disabled="formSubmitted"
              v-model="youtubeURL"
            />
            <button
              :class="{
                      'bg-gray-300 hover:bg-gray-400': !formSubmitted,
                      'cursor-not-allowed bg-gray-400': formSubmitted,
                      }"
              class="inline px-3 py-1 font-bold rounded-full"
              v-on:click="processYouTubeURL"
            >
              <span v-show="formSubmitted">
                <i class="fa fa-spinner fa-spin fa-fw"></i>
              </span>
              <span v-show="!formSubmitted">
                <i class="fab fa-youtube fa-fw"></i>
              </span>
            </button>

            <div
              class="text-red-500 mt-3 text-xs font-bold"
              v-if="formErrors && formErrors.youtubeURL !== undefined"
            >{{ formErrors.youtubeURL[0] }}</div>
          </div>
        </slide-up-down>

        <slide-up-down :active="step == 2" :duration="300" class="p-5">
          <div class="mx-auto relative h-32 w-32 mb-5">
            <img
              :src="sample.thumbnail == '' ? sample.youtube_video.thumbnail_url : sample.thumbnail"
              class="w-32 h-32 rounded-full border-2 border-gray-300 absolute object-cover top-0 bottom-0 left-0 right-0 shadow-lg"
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
          <div class="mb-3">
            <div class="text-xs mb-1">
              Sample
              <span class="text-red-500">*</span>
            </div>

            <sample-player :sample="sample" :autoload="false" :autoplay="false" ref="samplePlayer"></sample-player>

            <div class="flex items-center border-gray-300 border rounded w-full px-3 py-2 relative">
              <div class="absolute px-5 top-0 bottom-0 left-0 right-0" v-if="sample.waveform">
                <img
                  :src="'/storage/' + sample.waveform"
                  class="w-full h-full"
                  style="opacity: 0.2;"
                />
              </div>
              <div class="mr-3">
                <i class="fa fa-fw fa-spinner fa-spin" v-show="!processingComplete"></i>
                <i class="fa fa-fw fa-check-circle text-teal-400" v-show="processingComplete"></i>
              </div>
              <div class="flex-1" v-if="importType == 'mp3'">
                <div class="text-xs mb-1">
                  {{ files.audio.name }}
                  <span class="text-gray-500">({{ uploadProgress }}%)</span>
                </div>
                <div class="border-gray-300 border rounded w-full h-2 relative">
                  <div class="h-full bg-teal-400" :style="{width: uploadProgress + '%'}"></div>
                </div>
              </div>
              <div
                class="flex-1 flex items-center"
                v-if="importType == 'youtube' && sample.youtube_video != ''"
              >
                <div>
                  <img :src="sample.youtube_video.thumbnail_url" class="h-10 rounded mr-3" />
                </div>
                <div class="flex-1 text-xs">
                  <i class="fab fa-youtube mr-1"></i>
                  <span class="font-bold">{{ sample.youtube_video.title }}</span>
                  <br />
                  {{ sample.youtube_video.author_name }}
                </div>
              </div>
            </div>
          </div>

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
          <div>
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
                      'bg-gray-300 hover:bg-gray-400': !formSubmitted && processingComplete,
                      'cursor-not-allowed bg-gray-400': formSubmitted || !processingComplete,
                      }"
              class="inline-block px-3 py-1 font-bold rounded-full"
              v-on:click="submit"
            >
              <span v-show="formSubmitted || !processingComplete">
                <i class="fa fa-spinner fa-spin fa-fw"></i>
              </span>
              <span v-show="!formSubmitted && processingComplete">
                <i class="fa fa-upload mr-1"></i> Ajouter
              </span>
            </a>
          </div>
        </slide-up-down>
      </div>
    </div>
  </div>
</template>

<script>
import SamplePlayer from "./SamplePlayer";
let uploadMaxSize = 10 * 1048576; // 10 Mo
let mimesTypes = ["audio/mpeg", "audio/mp3"];
import axios from "axios";
import _ from "lodash";

export default {
  components: {
    SamplePlayer
  },
  data() {
    return {
      importType: "mp3",
      step: 1,
      youtubeURL: "",
      sample: {
        id: "",
        thumbnail: "/img/default.png",
        waveform: "",
        name: "",
        tags: [],
        description: "",
        settings: {},
        youtube_video: ""
      },
      files: {
        audio: "",
        thumbnail: ""
      },
      fileInput: "",
      uploadProgress: 0,
      processingComplete: false,
      uploadError: "",
      formErrors: {},
      formSubmitted: false,
      youtubeImportedVideo: "",
      currentTag: ""
    };
  },
  mounted() {
    let dropzone = document.getElementById("dropzone"),
      thumbnail = document.getElementById("thumbnail");

    dropzone.addEventListener("dragenter", e => {
      e.preventDefault();

      dropzone.classList.add("border-teal-400");
    });
    dropzone.addEventListener("dragover", e => {
      e.preventDefault();

      dropzone.classList.add("border-teal-400");
    });
    dropzone.addEventListener("dragleave", e => {
      e.preventDefault();

      dropzone.classList.remove("border-teal-400");
    });
    dropzone.addEventListener("drop", e => {
      e.preventDefault();

      dropzone.classList.remove("border-teal-400");

      dropzone.classList.remove("border-red-500");
      this.uploadError = null;
      this.processAudioFile(e.dataTransfer.files[0]);
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
      this.uploadError = null;
      this.processThumbnailFile(e.dataTransfer.files[0]);
    });
  },
  methods: {
    onAudioInputChange(e) {
      this.processAudioFile(e.target.files[0]);
    },
    processAudioFile(file) {
      let el = document.getElementById("dropzone");

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
            this.sample.id = response.data.id;
            this.sample.waveform = response.data.waveform;
            this.sample.audio = response.data.audio;

            this.$refs.samplePlayer.load();
          },
          error => {
            document.getElementById("dropzone").classList.add("border-red-500");
            if (error.response) {
              this.uploadError = error.response.data.errors.audio[0];
            }
            this.step = 1;
          }
        );
    },
    processYouTubeURL() {
      if (this.formSubmitted) return;

      let formData = new FormData();
      formData.append("youtubeURL", this.youtubeURL);

      this.importType = "youtube";
      this.formSubmitted = true;

      axios.post("/samples/preflight/youtube", formData).then(
        response => {
          this.sample.id = response.data.id;
          this.sample.name = response.data.name;
          this.sample.youtube_video = response.data.youtube_video;
          this.sample.thumbnail = "";
          this.step = 2;
          this.formSubmitted = false;
          this.formErrors = {};

          axios
            .get("/samples/" + response.data.id + "/process-youtube")
            .then(response => {
              this.processingComplete = true;
              this.sample.audio = response.data.audio;
              this.$refs.samplePlayer.load();
            });
        },
        error => {
          this.formSubmitted = false;
          this.formErrors = error.response.data.errors;
        }
      );
    },
    onThumbnailInputChange(e) {
      this.processThumbnailFile(e.target.files[0]);
    },
    processThumbnailFile(file) {
      let el = document.getElementById("thumbnail");

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
        this.sample.thumbnail = e.target.result;
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
      let formData = new FormData();

      if (!this.sample.id) return;
      if (this.formSubmitted || !this.processingComplete) return;

      formData.append("id", this.sample.id);
      formData.append("name", this.sample.name);

      this.sample.tags.forEach((tag, i) => {
        formData.append("tags[" + i + "]", tag);
      });

      formData.append("description", this.sample.description);
      formData.append("thumbnail", this.files.thumbnail);

      this.formSubmitted = true;

      axios
        .post("/samples", formData, {
          headers: { "Content-Type": "multipart/form-data" }
        })
        .then(
          response => {
            window.location = "/samples/" + response.data.id;
          },
          error => {
            this.formSubmitted = false;
            this.formErrors = error.response.data.errors;
          }
        );
    }
  }
};
</script>