<template>
  <div class="card p-4" v-if="currentSample">
    <div class="mx-auto relative h-32 w-32 mb-5">
      <img
        :src="currentSample.thumbnail_url"
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
      <input type="file" id="thumbnailInput" v-on:change="onThumbnailInputChange" class="hidden" />
    </div>
    <div
      class="text-red-500 my-3 text-xs font-bold"
      v-if="formErrors && formErrors.thumbnail !== undefined"
    >{{ formErrors.thumbnail[0] }}</div>

    <div class="mb-3">
      <div class="text-xs mb-1">
        Nom
        <span class="text-red-500">*</span>
      </div>
      <input
        type="text"
        class="form-control w-full"
        v-model="currentSample.name"
        :disabled="formSubmitted"
      />
      <div
        class="text-red-500 mt-3 text-xs font-bold"
        v-if="formErrors && formErrors.name !== undefined"
      >{{ formErrors.name[0] }}</div>
    </div>
    <div class="mb-3">
      <div class="text-xs mb-1">Tags</div>
      <input
        type="text"
        class="form-control w-full"
        @keyup.enter="appendTag()"
        v-model="currentTag"
        :disabled="formSubmitted"
      />
      <div
        class="text-red-500 mt-3 text-xs font-bold"
        v-if="formErrors && formErrors.tags !== undefined"
      >{{ formErrors.tags[0] }}</div>

      <div class="text-muted mt-3 text-xs" v-if="currentSample.tags.length == 0">
        Exemples :
        <i class="fas fa-hashtag"></i> olinux
        <i class="fas fa-hashtag"></i> meme
        <i class="fas fa-hashtag"></i> 4sucres
      </div>

      <div class="my-3">
        <div
          class="btn btn-xs btn-secondary hover:line-through cursor-pointer mr-1 inline-block"
          v-for="(tag, i) in currentSample.tags"
          :key="i"
          v-on:click="removeTag(i)"
        >
          <i class="fas fa-hashtag"></i>
          {{ tag }}
        </div>
      </div>
    </div>
    <div>
      <div class="mb-3">
        <div class="text-xs mb-1">Description</div>
        <textarea
          placeholder="Tu peux renseigner la source du sample et son histoire"
          type="text"
          class="form-control w-full h-32"
          v-model="currentSample.description"
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
                      'btn-primary': !formSubmitted,
                      'cursor-not-allowed btn-secondary': formSubmitted,
                      }"
        class="btn"
        v-on:click="submit"
      >
        <span v-show="formSubmitted">
          <i class="fa fa-spinner fa-spin fa-fw"></i>
        </span>
        <span v-show="!formSubmitted">Valider</span>
      </a>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  props: ["sample", "tags"],
  data() {
    return {
      currentSample: null,
      formErrors: {},
      formSubmitted: false,
      currentTag: "",
      files: {
        thumbnail: null
      }
    };
  },
  mounted() {
    this.currentSample = this.sample;
    this.currentSample.thumbnail = "/storage/" + this.currentSample.thumbnail;
    this.currentSample.tags = this.tags;

    setTimeout(this.mountDragAndDrop, 500);
  },
  methods: {
    mountDragAndDrop() {
      let thumbnail = document.getElementById("thumbnail");

      thumbnail.addEventListener("dragenter", this.dragEnter);
      thumbnail.addEventListener("dragover", this.dragEnter);
      thumbnail.addEventListener("dragleave", this.dragLeave);
      thumbnail.addEventListener("drop", this.drop);
    },
    dragEnter(e) {
      e.preventDefault();
      thumbnail.classList.add("border-teal-400");
    },
    dragLeave(e) {
      e.preventDefault();
      thumbnail.classList.remove("border-teal-400");
    },
    drop(e) {
      e.preventDefault();
      thumbnail.classList.remove("border-teal-400");
      thumbnail.classList.remove("border-red-500");
      this.processThumbnailFile(e.dataTransfer.files[0]);
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
        this.currentSample.thumbnail_url = e.target.result;
      };
      reader.readAsDataURL(file);
    },
    appendTag() {
      let safeTag = this.currentTag.trim().replace(/  */gi, "-");
      if (
        safeTag &&
        safeTag != "" &&
        this.currentSample.tags.indexOf(safeTag) == -1 &&
        safeTag.length < 30 &&
        this.currentSample.tags.length < 10
      ) {
        this.currentSample.tags.push(safeTag);
      }

      this.currentTag = "";
    },
    removeTag(i) {
      this.currentSample.tags.splice(i, 1);
    },
    submit() {
      let formData = new FormData();
      if (this.formSubmitted) return;

      formData.append("_method", "put");
      formData.append("name", this.currentSample.name);

      this.currentSample.tags.forEach((tag, i) => {
        formData.append("tags[" + i + "]", tag);
      });

      if (this.currentSample.description !== null)
        formData.append("description", this.currentSample.description);
      if (this.files.thumbnail)
        formData.append("thumbnail", this.files.thumbnail);

      this.formSubmitted = true;

      axios
        .post("/samples/" + this.currentSample.id, formData, {
          headers: { "Content-Type": "multipart/form-data" }
        })
        .then(
          response => {
            this.formSubmitted = false;
            window.location = "/samples/" + this.currentSample.id;
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