<template>
  <v-container fluid class="ma-0 pa-0 align-start child-flex">
    <nav-drawer @click="changeView" />
    <exams-data-table v-if="this.view == 'exams'" />
    <users-data-table v-if="this.view == 'users'" />
    <downloads-data-table v-if="this.view == 'downloads'" />
    <uploads-data-table v-if="this.view == 'uploads'" />
    <hfq-report v-if="this.view == 'hfqreport'" />
    <frequency-grid v-if="this.view == 'frequencygrid'" />
  </v-container>
</template>

<script>
import NavDrawer from "../components/NavDrawer.vue";
import ExamsDataTable from "../components/ExamsDataTable.vue";
import UsersDataTable from "../components/UsersDataTable.vue";
import DownloadsDataTable from "../components/DownloadsDataTable.vue";
import UploadsDataTable from "../components/UploadsDataTable.vue";
import HfqReport from "../components/HFQReport.vue";
import FrequencyGrid from "../components/FrequencyGrid.vue";

export default {
  components: {
    NavDrawer,
    ExamsDataTable,
    UsersDataTable,
    DownloadsDataTable,
    UploadsDataTable,
    HfqReport,
    FrequencyGrid,
  },

  data() {
    return {
      view: "downloads"
    };
  },

  methods: {
    changeView(view) {
      this.view = view;
    }
  }
};
</script>>