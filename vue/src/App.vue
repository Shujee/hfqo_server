<template>
  <v-app>
    <v-app-bar app color="primary" dark clipped>
      <span class="mr-5">HFQ SERVER</span>
      <v-btn to="/" text class="ml-5">Home</v-btn>
      <v-btn to="/about" text>About</v-btn>
      <v-spacer></v-spacer>

      <v-btn v-if="!loggedIn" to="/login" text>Login</v-btn>
      <v-btn v-else @click="logout" text>Logout</v-btn>
    </v-app-bar>
   
    <v-content>
      <v-container fluid fill-height class="ma-0 pa-0">
        <router-view />
      </v-container>
    </v-content>

    <confirm-dialog ref="confirm"></confirm-dialog>
  </v-app>
</template>

<script>
import ConfirmDialog from "./components/ConfirmDialog";
import { mapGetters } from "vuex";

export default {
  name: "App",
  components: {
    ConfirmDialog,
  },
  computed: {
  ...mapGetters(["loggedIn"]),
  },
  mounted() {
    this.$root.$confirm = this.$refs.confirm;
  },
  methods: {
    logout() {
      this.$store.dispatch("logout");
      this.$router.push("/login");
    }
  },
};
</script>