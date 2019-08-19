<template>
  <div>
    <div class="card p-4 mb-4">
      <div class="mb-3">
        <div class="text-xs mb-1">
          Lien
          <span class="text-red-500">*</span>
        </div>
        <input type="text" class="form-control w-full" v-model="url" :disabled="formSubmitted" />
        <div
          class="text-red-500 mt-3 text-xs font-bold"
          v-if="formErrors && formErrors.url !== undefined"
        >{{ formErrors.url[0] }}</div>
      </div>
    </div>
    <div class="text-right">
      <button
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
        <span v-show="!formSubmitted">Importer</span>
      </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import _ from "lodash";

export default {
  data() {
    return {
      url: "",
      processingComplete: false,
      formErrors: "",
      formSubmitted: false
    };
  },
  methods: {
    submit() {
      if (this.formSubmitted) return;

      let formData = new FormData();
      formData.append("url", this.url);

      this.formSubmitted = true;

      axios.post("/samples/preflight/url", formData).then(
        response => {
          window.location = "/samples/" + response.data.id + "/edit";
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