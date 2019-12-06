<template>
  <v-layout wrap justify-center>
    <v-flex lg3 md6 sm8 xs12>
      <v-form action="#" ref="form" @submit.prevent="login">
        <v-row class="justify-center my-lg-12 my-xs-6">
          <h1 class="text-center">HFQ Admin Login</h1>
        </v-row>

        <v-row class="justify-center my-lg-12 my-xs-6">
          <v-avatar size="128" class="elevation-10">
            <img src="../assets/AdminPhoto.png" alt="alt" />
          </v-avatar>
        </v-row>

        <v-row class="mt-lg-12 mt-xs-6">
          <v-text-field
            ref="txtEmail"
            label="Email"
            name="email"
            type="text"
            color="primary"
            class="text-bold"
            autofocus
            v-model="email"
            :rules="[rules.required, rules.email]"
            validate-on-blur
          ></v-text-field>
        </v-row>

        <v-row>
          <v-text-field
            id="password"
            label="Password"
            name="password"
            color="primary"
            type="password"
            v-model="password"
            :rules="[rules.required, rules.password]"
            validate-on-blur
            v-on:keyup.enter="login"
          ></v-text-field>
        </v-row>

        <v-row class="my-lg-12 my-xs-6">
          <v-btn
            color="primary"
            class="green-btn"
            height="60"
            dark
            block
            @click.stop="login"
          >Login</v-btn>
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
    logging_in: false,
    rules: {
      required: value => !!value || "Required.",
      email: [
        v => !!v || "E-mail is required",
        v => /.+@.+\..+/.test(v) || "E-mail must be valid"
      ],
      password: value => {
        const pattern = /^[\w\d]{6,15}$/;
        return (
          pattern.test(value) ||
          "Password must be 8 to 15 characters long. Alphabet and digits only."
        );
      }
    }
  }),

  methods: {
    login() {
      if (this.$refs.form.validate()) {
        this.logging_in = true;

        this.$store
          .dispatch("login", {
            email: this.email,
            password: this.password
          })
          .then(() => {
            this.logging_in = false;
            this.$router.push('home');
          })
          .catch(err => {
            var msg;
            if (err.response.status == 401)
              msg = "Invalid e-mail and/or password";
            else 
              msg = "An error occurred. Check your Internet connection.";

            this.logging_in = false;
            this.$root.$confirm.open("Error", msg, {
              color: "red",
              show_cancel: false,
              icon: "mdi-alert-circle"
            });
          });
      }
    }
  }
};
</script>
<style scoped>
.green-btn {
  background-color: #9db3e7;
  border-radius: 25px;
  box-shadow: 0 10px 30px 0 #9db3e7;
  -moz-box-shadow: 0 10px 30px 0 #9db3e7;
  -webkit-box-shadow: 0 10px 30px 0 #9db3e7;
  -o-box-shadow: 0 10px 30px 0 #9db3e7;
  -ms-box-shadow: 0 10px 30px 0 #9db3e7;
  -webkit-transition: all 0.4s;
  -o-transition: all 0.4s;
  -moz-transition: all 0.4s;
  transition: all 0.4s;
}

.v-input {
  font-weight: bold;
}
</style>