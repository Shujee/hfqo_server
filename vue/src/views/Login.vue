<template>
  <v-layout wrap align-center justify-center>
    <v-flex lg3 md6 sm8 xs12>
      <v-form action="#" ref="form" @submit.prevent="login">
        <v-row class="justify-center mb-5">
        <h1 class="text-center">HFQ Admin Login</h1>
        </v-row>

        <v-text-field
          ref="txtEmail"
          label="Email"
          name="email"
          prepend-icon="mdi-at"
          type="text"
          autofocus
          v-model="email"
          :rules="[rules.required, rules.email]"
          validate-on-blur
        ></v-text-field>

        <v-text-field
          id="password"
          label="Password"
          name="password"
          prepend-icon="mdi-lock"
          type="password"
          v-model="password"
          :rules="[rules.required, rules.password]"
          validate-on-blur
          v-on:keyup.enter="login"
        ></v-text-field>

        <v-row class="mt-5">
          <div class="flex-grow-1"></div>
          <v-btn color="primary" @click.stop="login">Login</v-btn>
        </v-row>
      </v-form>

      <v-progress-circular v-if="logging_in" indeterminate dark />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "Login",

  data: () => ({
    email: "",
    password: "",
    rules: {
      required: value => !!value || "Required.",
      email: [
        v => !!v || 'E-mail is required',
        v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
      ],
      password: value => {
        const pattern = /^[\w\d]{6,15}$/;
        return (
          pattern.test(value) ||
          "Password must be 8 to 15 characters long. Alphabet and digits only."
        );
      }
    }
  })
};
</script>