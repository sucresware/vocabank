<template>
  <div>
    <div v-if="!infinite" class="flex justify-between my-3">
      <button
        class="mr-auto btn btn-secondary"
        :class="{ disabled: !prevPageUrl }"
        v-on:click="switchPage()"
      >
        <i class="fas fa-angle-left"></i>
      </button>
      <button
        class="ml-auto btn btn-secondary"
        :class="{ disabled: !nextPageUrl }"
        v-on:click="switchPage(true)"
      >
        <i class="fas fa-angle-right"></i>
      </button>
    </div>

    <div v-for="sample in samples" :key="sample.id">
      <div class="card mb-2">
        <sample-preview :sample="sample"></sample-preview>
      </div>
    </div>

    <div v-if="infinite">
      <infinite-loading @infinite="infiniteHandler" spinner="waveDots">
        <div slot="no-more"></div>
        <div slot="no-results"></div>
      </infinite-loading>
    </div>

    <div v-if="!infinite" class="flex justify-between my-3">
      <button
        class="mr-auto btn btn-secondary"
        :class="{ disabled: !prevPageUrl }"
        v-on:click="switchPage()"
      >
        <i class="fas fa-angle-left"></i>
      </button>
      <button
        class="ml-auto btn btn-secondary"
        :class="{ disabled: !nextPageUrl }"
        v-on:click="switchPage(true)"
      >
        <i class="fas fa-angle-right"></i>
      </button>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  props: ["filter", "paginator", "infinite"],
  data() {
    return {
      nextPageUrl: "",
      prevPageUrl: "",
      samples: [],
    };
  },
  methods: {
    infiniteHandler($state) {
      axios
        .get(this.nextPageUrl)
        .then(
          response => {
            this.nextPageUrl = response.data.next_page_url;
            if (response.data.data.length) {
              this.samples.push(...response.data.data);
              $state.loaded();
            } else {
              $state.complete();
            }
          },
          error => {
            $state.complete();
          }
        );
    },
    switchPage(next) {
      let intended = (next) ? this.nextPageUrl : this.prevPageUrl;
      axios
        .get(intended)
        .then(response => {
          if (response.data.data.length) {
            this.nextPageUrl = response.data.next_page_url;
            this.prevPageUrl = response.data.prev_page_url;
            this.samples = response.data.data;
            history.pushState(
              {},
              null,
              intended
            );
          }
        });
    }
  },
  mounted: function() {
    this.samples = this.paginator.data;
    this.nextPageUrl = this.paginator.next_page_url;
    this.prevPageUrl = this.paginator.prev_page_url;
  }
};
</script>