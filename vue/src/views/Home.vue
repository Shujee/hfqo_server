<template>
  <v-container fluid class="ma-0 pa-0 align-start child-flex">
    <nav-drawer @click="changeView" />
    <ExamsDataTable v-if="this.view == 'exams'" />
    <UsersDataTable v-if="this.view == 'users'" />
  </v-container>
</template>

<script>
import NavDrawer from "../components/NavDrawer.vue";
import ExamsDataTable from "../components/ExamsDataTable.vue";
import UsersDataTable from "../components/UsersDataTable.vue";

export default {
  components: {
    NavDrawer,
    ExamsDataTable,
    UsersDataTable
  },

  data() {
    return {
      view: "exams"
    };
  },

  methods: {
    changeView(view) {
      this.view = view;
    }
  }
};
</script>>