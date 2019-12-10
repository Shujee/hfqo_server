<template>
  <v-card class="ma-0 pa-0">
    <v-card-title class="ma-0 py-1">
      <v-icon>mdi-download</v-icon>
      Downloads
      <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
      ></v-text-field>
    </v-card-title>

    <v-data-table
      style="height: calc(100vh - 120px); overflow: auto;"
      full-width
      :headers="headers"
      :items="downloads"
      :loading="loading"
      class="elevation-1 ma-0"
      :items-per-page="25"
      :search="search"
      :readonly="true"
      :footer-props="{
        'items-per-page-options': [10,25,50]
      }"
      :sort-by="['created_at']"
      :sort-desc="[true]"
    >
      <template v-slot:item.user_name="{ item }">
        <v-avatar size="32">
          <v-icon>mdi-account</v-icon>
        </v-avatar>
        <span class="pl-4">{{ item.user_name }}</span>
      </template>

      <template v-slot:item.machine_name="{ item }">
        <v-avatar size="32">
          <v-icon>mdi-desktop-classic</v-icon>
        </v-avatar>
        <span class="pl-4">{{ item.machine_name }}</span>
      </template>

      <template v-slot:item.ip="{ item }">
        <span class="text-mono">{{ item.ip }}</span>
      </template>

      <template v-slot:item.created_at="{ item }">
        <v-layout justify-center>
          <v-chip success outlined ml-3>
            <v-icon left outline mr-2>mdi-clock</v-icon>
            {{item.age}}
          </v-chip>
        </v-layout>
      </template>
    </v-data-table>
  </v-card>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      loading: false,
      search: "",
      headers: [
        { text: "User Name", value: "user_name", sortable: false },
        { text: "User Email", value: "user_email", sortable: false },
        { text: "Master File", value: "exam_name", sortable: false },
        {
          text: "IP Address",
          value: "ip",
          sortable: false,
          class: "text-mono"
        },
        { text: "Machine Name", value: "machine_name", sortable: false },
        {
          text: "Downloaded",
          value: "created_at",
          align: "center",
          sortable: false
        }
      ]
    };
  },

  computed: {
    ...mapGetters(["downloads"])
  },

  mounted() {
    this.loading = true;
    this.$store
      .dispatch("fetchDownloads")
      .then(() => (this.loading = false))
      .catch(() => (this.loading = false));
  }
};
</script>

<style>
.text-mono {
  font-family: monospace;
}

/* This style only works in non-scoped styles */
/* see https://stackoverflow.com/questions/54508774/how-do-i-make-a-striped-table-in-vuetify-v-data-table */
tbody tr:nth-of-type(odd) {
   background-color: rgba(0, 0, 0, .1);
 }
</style>