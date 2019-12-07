<template>
  <v-container fluid fill-height class="ma-0 pa-0 align-start child-flex">
    <nav-drawer :width="250" />
    <ExamsDataTable />
  </v-container>
</template>

<script>
import NavDrawer from "../components/NavDrawer.vue";
import ExamsDataTable from "../components/ExamsDataTable.vue";

export default {
  components: {
    NavDrawer,
    ExamsDataTable
  }
};
</script>>