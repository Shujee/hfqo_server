<template>
  <v-app>
    <v-app-bar app color="primary" dark>
      <span class="mr-5">HFQ SERVER</span>
      <v-btn to="/" text class="ml-5">Home</v-btn>
      <v-btn to="/about" text>About</v-btn>
      <v-spacer></v-spacer>
      <v-btn to="/login" text>Login</v-btn>
    </v-app-bar>

    <v-content>
      <v-container fluid>
        <router-view />
      </v-container>
    </v-content>
  </v-app>
</template>
<script>
export default {
  name: "App"
};
</script>