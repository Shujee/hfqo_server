<template>
  <v-navigation-drawer app clipped permanent color="primary" mini-variant :mini-variant-width="80" :expand-on-hover="true">
    <v-list dark three-line>
        <v-list-item v-for="item in items"
          :key="item.title"
          @click="emitClick(item)"
        >
          <v-list-item-avatar>
            <v-icon size="32">{{item.icon}}</v-icon>
          </v-list-item-avatar>

          <v-list-item-content>
            <v-list-item-title v-html="item.title"></v-list-item-title>
            <v-list-item-subtitle v-html="item.subtitle"></v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
export default {
  name: "NavDrawer",
  data() {
    return {
      items: [
        {
          title: "Master Files",
          subtitle: "Manage exams uploaded using Multi-DF",
          icon: "mdi-file-document",
          view: "exams"
        },
        {
          title: "Users",
          subtitle: "Create, edit and delete users",
          icon: "mdi-account",
          view: "users"
        },
        {
          title: "Downloads (HFQApp)",
          subtitle: "Exams downloads by users through HFQApp",
          icon: "mdi-download",
          view: "downloads"
        },
        {
          title: "Uploads (HFQApp)",
          subtitle: "Results uploaded by users through HFQApp",
          icon: "mdi-upload",
          view: "uploads"
        },
        {
          title: "HFQ Report",
          subtitle: "Generate high-frequency questions report",
          icon: "mdi-file-chart",
          view: "hfqreport"
        },
        {
          title: "Frequency Grid",
          subtitle: "Manually enter QA indexes to update HFQ Report",
          icon: "mdi-grid",
          view: "frequencygrid"
        },
      ],
      right: null
    };
  },

  methods: {
    emitClick(item) {
      this.$emit("click", item.view);
    }
  }
};
</script>