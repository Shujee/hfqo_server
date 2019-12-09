<template>
  <v-navigation-drawer app clipped permanent color="primary" mini-variant>
    <v-list dark>
      <v-list-item disabled>
        <v-list-item-avatar color="white" size="48">
          <v-icon size="32" color="primary">mdi-account-supervisor</v-icon>
        </v-list-item-avatar>
      </v-list-item>

      <v-divider color="white" class="mb-6"></v-divider>

      <v-list-item-group v-model="item">
        <v-list-item v-for="(item, i) in items" :key="i" @click="emitClick(item)">
          <v-list-item-icon>
            <v-icon size="32">{{ item.icon }}</v-icon>
          </v-list-item-icon>
        </v-list-item>
      </v-list-item-group>
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
          subtitle: "Add or manage master documents",
          icon: "mdi-file-document",
          view: "exams"
        },
        {
          title: "Users",
          subtitle: "Create users, assign permissions",
          icon: "mdi-account",
          view: "users"
        },
        {
          title: "HFQ Report",
          subtitle: "Generate high-frequency questions report",
          icon: "mdi-file-chart",
          view: "report"
        }
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