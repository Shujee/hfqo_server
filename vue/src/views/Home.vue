<template>
  <v-container fluid class="ma-0 pa-0 align-start child-flex">
    <nav-drawer @click="changeView" />
    <ExamsDataTable v-if="this.view == 'exams'" />
    <UsersDataTable v-if="this.view == 'users'" />
    <DownloadsDataTable v-if="this.view == 'downloads'" />
    <UploadsDataTable v-if="this.view == 'uploads'" />
    <HFQReport v-if="this.view == 'hfqreport'" />
  </v-container>
</template>

<script>
import NavDrawer from "../components/NavDrawer.vue";
import ExamsDataTable from "../components/ExamsDataTable.vue";
import UsersDataTable from "../components/UsersDataTable.vue";
import DownloadsDataTable from "../components/DownloadsDataTable.vue";
import UploadsDataTable from "../components/UploadsDataTable.vue";
import HFQReport from "../components/HFQReport.vue";

export default {
  components: {
    NavDrawer,
    ExamsDataTable,
    UsersDataTable,
    DownloadsDataTable,
    UploadsDataTable,
    HFQReport
  },

  data() {
    return {
      view: "hfqreport"
    };
  },

  methods: {
    changeView(view) {
      this.view = view;
    }
  }
};
</script>>