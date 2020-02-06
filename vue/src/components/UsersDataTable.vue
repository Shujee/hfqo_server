<template>
  <v-card class="ma-0 pa-0">
    <v-card-title class="ma-0 py-1">
      <v-icon class="mr-2">mdi-account</v-icon>Users
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
      show-expand
      single-expand
      item-key="id"
      :footer-props="{
        'items-per-page-options': [10,25,50]
      }"
      :sort-by="['updated_at']"
      :sort-desc="[true]"
    >
      <template v-slot:expanded-item="{ item }">
        <td :colspan="headers.length + 1" v-if="item.activity.length > 0">
          <v-card flat class="my-1">
            <v-card-text>
              <activity-data-table :username="item.name" :activities="item.activity" />
            </v-card-text>
          </v-card>
        </td>
        <td
          :colspan="headers.length"
          v-else
          class="justify-center align-center text-center"
        >No activity</td>
      </template>

      <template v-slot:item.name="{ item }">
        <v-avatar size="32">
          <v-icon>mdi-account</v-icon>
        </v-avatar>
        <span class="pl-4">{{ item.name }}</span>
      </template>

      <template v-slot:item.type="{ item }">
        <v-chip
          :color="getColor(item.type)"
          dark
        >{{ item.type == 1 ? 'Admin' : (item.type == 2 ? 'Associate' : 'User') }}</v-chip>
      </template>

      <template v-slot:item.updated_at="{ item }">
        <v-layout justify-center>
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-chip success outlined ml-3 v-on="on">
                <v-icon left outline mr-2>mdi-clock</v-icon>
                {{item.age}}
              </v-chip>
            </template>
            <span>{{new Date(item.updated_at).toString()}}</span>
          </v-tooltip>
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
import ActivityDataTable from "./ActivityDataTable";

export default {
  components: {
    UserModal,
    ActivityDataTable
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
        { text: "Type", value: "type", sortable: false, align: "center" },
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
    getColor(type) {
      if (type == 1) return "red";
      else if (type == 2) return "blue";
      else return "green";
    },

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
      this.user = { name: null, email: null, password: null };
      this.dialog = true;
    }
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
