<template>
  <v-card class="ma-0 pa-0">
    <v-card-title class="ma-0 py-1">
      <v-icon class="mr-2">mdi-download</v-icon>Downloads
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
      :single-expand="true"
      :expanded.sync="expanded"
      show-expand
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

      <template v-slot:item.city="{ item }">
        <span>{{ item.city }}, {{ item.country }}</span>
      </template>

      <template v-slot:item.created_at="{ item }">
        <v-layout justify-center>
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-chip success outlined ml-3 v-on="on">
                <v-icon left outline mr-2>mdi-clock</v-icon>
                {{item.age}}
              </v-chip>
            </template>
            <span>{{new Date(item.created_at).toString()}}</span>
          </v-tooltip>
        </v-layout>
      </template>

      <template v-slot:expanded-item="{ headers }">
        <td :colspan="headers.length">
          <v-layout class="justify-center" v-if="media_loading">
            <v-progress-circular indeterminate color="primary"></v-progress-circular>
          </v-layout>

          <v-expand-transition v-else>
            <images-grid :images="media" />
          </v-expand-transition>
        </td>
      </template>

      <template v-slot:item.data-table-expand="{ item }">
        <v-icon @click="expandSnapshots(item)">mdi-camera</v-icon>
      </template>
    </v-data-table>
  </v-card>
</template>

<script>
import { mapGetters } from "vuex";
import ImagesGrid from "../components/ImagesGrid";

export default {
  components: {
    ImagesGrid
  },

  data() {
    return {
      loading: false,
      media_loading: true,
      media: [],
      search: "",
      expanded: [],
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
        { text: "Location", value: "city", sortable: false },
        { text: "Machine Name", value: "machine_name", sortable: false },
        {
          text: "Downloaded",
          value: "created_at",
          align: "center",
          sortable: false
        },
        { text: "", value: "data-table-expand", sortable: false }
      ]
    };
  },

  computed: {
    ...mapGetters(["downloads"])
  },

  methods: {
    expandSnapshots(item) {
      if (this.expanded[0] === item) this.expanded = [];
      else this.expanded = [item];
    },
  },

  mounted() {
    this.loading = true;
    this.$store
      .dispatch("fetchDownloads")
      .then(() => (this.loading = false))
      .catch(() => (this.loading = false));
  },

  watch: {
    expanded: function(val) {
      this.media = [];
      this.media_loading = true;
      this.$store.dispatch("fetchSnapshots", val[0].id).then(response => {
        this.media = response.data.map(img => {
          return { name: img.filename, thumb: img.thumb_filename, alt: img.timestamp };
        });
        this.media_loading = false;
      });
    }
  }
};
</script>

<style>
.text-mono {
  font-family: monospace;
}
</style>