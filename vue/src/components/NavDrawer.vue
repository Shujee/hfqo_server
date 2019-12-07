<template>
  <v-navigation-drawer permanent :width="width">
    <v-list-item>
      <v-list-item-content>
        <v-list-item-title class="title">HFQ Admin</v-list-item-title>
        <v-list-item-subtitle>HFQ Control Panel</v-list-item-subtitle>
      </v-list-item-content>
    </v-list-item>

    <v-divider></v-divider>

    <v-list dense nav>
      <v-list-item v-for="item in items" :key="item.title" link>
        <v-list-item-icon>
          <v-icon>{{ item.icon }}</v-icon>
        </v-list-item-icon>

        <v-list-item-content>
          <v-list-item-title>{{ item.title }}</v-list-item-title>
          <v-list-item-subtitle>{{ item.subtitle }}</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
  export default {
    name: "NavDrawer",
    props: {
      width: Number,
    },
    data () {
      return {
        items: [
          { title: 'Master Files', subtitle: 'Add or manage master documents', icon: 'mdi-file-document' },
          { title: 'Users', subtitle: 'Create users, assign permissions', icon: 'mdi-account' },
          { title: 'HFQ Report',subtitle: 'Generate high-frequency questions report', icon: 'mdi-file-chart' },
        ],
        right: null,
      }
    },
  }
</script>