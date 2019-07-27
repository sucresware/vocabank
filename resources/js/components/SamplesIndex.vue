<template>
  <div>
    <div class="bg-white border rounded shadow mb-3">
      <div v-for="(sample, i) in samples" :key="sample.id">
        <sample-preview :sample="sample"></sample-preview>
      </div>
    </div>

    <div v-if="infinite">
      <infinite-loading @infinite="infiniteHandler" spinner="waveDots">
        <div slot="no-more"></div>
        <div slot="no-results"></div>
      </infinite-loading>
    </div>
    <div v-if="!infinite" class="flex justify-between mb-3">
      <div
        class="mr-auto cursor-pointer px-3 py-1 font-bold rounded-full hover:bg-gray-300 text-xs"
        v-show="page !== 1"
        v-on:click="loadPage(page-1)"
      >
        <i class="fas fa-angle-left"></i>
      </div>
      <div
        class="ml-auto cursor-pointer px-3 py-1 font-bold rounded-full hover:bg-gray-300 text-xs"
        v-show="page !== lastPage"
        v-on:click="loadPage(page+1)"
      >
        <i class="fas fa-angle-right"></i>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  props: ["filter", "paginator", "infinite"],
  data() {
    return {
      page: "",
      path: "",
      samples: [],
      // infinite props :
      pageTop: "",
      // not-infinite props :
      lastPage: 0
    };
  },
  methods: {
    infiniteHandler($state) {
      axios
        .get(this.path, {
          params: { page: (this.page += 1) }
        })
        .then(
          response => {
            if (response.data.data.length) {
              // this.page += 1;
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
    loadPage(page) {
      let vm = this;
      axios
        .get(this.path, {
          params: { page: page }
        })
        .then(response => {
          if (response.data.data.length) {
            vm.page = page;
            vm.lastPage = response.data.last_page;
            vm.samples = response.data.data;
            history.pushState(
              {},
              null,
              response.data.path + "?page=" + vm.page
            );
          }
        });
    }
    // infiniteHandlerTop($state) {
    //   if (this.pageTop <= 0) return $state.complete();

    //   axios
    //     .get("/api/v0/discussions/" + this.discussionId, {
    //       params: { page: this.pageTop }
    //     })
    //     .then(({ data }) => {
    //       if (data.data.length) {
    //         this.pageTop -= 1;
    //         this.posts = data.data.concat(this.posts);
    //         $state.loaded();
    //       } else {
    //         $state.complete();
    //       }
    //     });
    // }
  },
  mounted: function() {
    console.log(this.paginator);
    this.samples = this.paginator.data;
    this.page = this.paginator.current_page;
    this.path = this.paginator.path;

    // if (this.initPage) {
    //   this.page = this.initPage;
    //   // this.pageTop = this.initPage;
    // } else {
    //   this.page = 1;
    //   // this.pageTop = 1;
    // }
  }
};
</script>