<template>
  <v-card class="ma-0 pa-0">
    <v-card-title class="ma-0 py-1">
      <v-icon class="mr-2">mdi-account</v-icon>
      Users
      <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
      ></v-text-field>

      <v-tooltip bottom>
        <template v-slot:activator="{ on }">
          <v-btn color="primary" fab small class="ml-4" @click.stop="createUser">
            <v-icon>mdi-plus</v-icon>
          </v-btn>
        </template>
        <span>Create New User</span>
      </v-tooltip>
    </v-card-title>

    <v-data-table
      style="height: calc(100vh - 120px); overflow: auto;"
      full-width
      :headers="headers"
      :items="users"
      :loading="loading"
      class="elevation-1 ma-0"
      :items-per-page="25"
      :search="search"
      :readonly="true"
      :footer-props="{
        'items-per-page-options': [10,25,50]
      }"
      :sort-by="['updated_at']"
      :sort-desc="[true]"
    >
      <template v-slot:item.name="{ item }">
        <v-avatar size="32">
          <v-icon>mdi-account</v-icon>
        </v-avatar>
        <span class="pl-4">{{ item.name }}</span>
      </template>

      <template v-slot:item.updated_at="{ item }">
        <v-layout justify-center>
          <v-chip success outlined ml-3>
            <v-icon left outline mr-2>mdi-clock</v-icon>
            {{item.age}}
          </v-chip>
        </v-layout>
      </template>

      <template v-slot:item.actions="{ item }">
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
            <v-icon @click="editUser(item)" class="mr-2" v-on="on">mdi-pencil</v-icon>
          </template>
          <span>Edit user record</span>
        </v-tooltip>

        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
            <v-icon @click="deleteUser(item)" v-on="on">mdi-delete</v-icon>
          </template>
          <span>Delete this user</span>
        </v-tooltip>
      </template>
    </v-data-table>

    <user-modal v-model="dialog" :user="user" :editMode="editMode" />

    <v-overlay :value="processing">
      <v-progress-circular color="primary" indeterminate dark />
    </v-overlay>
  </v-card>
</template>

<script>
import { mapGetters } from "vuex";
import UserModal from "./UserModal";

export default {
  components: {
    UserModal,
  },

  data() {
    return {
      loading: false,
      processing: false,
      user: null,
      dialog: false,
      editMode: false,
      search: "",
      headers: [
        { text: "Name", value: "name", sortable: false },
        { text: "Email", value: "email", sortable: false },
        {
          text: "Last Updated",
          value: "updated_at",
          align: "center",
          sortable: false
        },
        { text: "", value: "actions", sortable: false, align: "end" }
      ]
    };
  },

  methods: {
    deleteUser: async function(user) {
      if (
        await this.$root.$confirm.open(
          "Delete",
          "Do you want to delete this user?",
          { color: "primary" }
        )
      ) {
        this.processing = true;
        this.$store
          .dispatch("deleteUser", user.id)
          .then(() => (this.processing = false))
          .catch(() => (this.processing = false));
      }
    },

    editUser: async function(user) {
      this.editMode = true;
      this.user = user;
      this.dialog = true;
    },
    
    createUser: async function() {
      this.editMode = false;
      this.user = {name: null, email: null, password: null};
      this.dialog = true;
    },
  },

  computed: {
    ...mapGetters(["users"])
  },

  mounted() {
    this.loading = true;
    this.$store
      .dispatch("fetchUsers")
      .then(() => (this.loading = false))
      .catch(() => (this.loading = false));
  }
};
</script>
