<template>
  <v-dialog v-model="show" max-width="450">
    <v-form action="#" ref="form">
      <v-card class="elevation-12">
        <v-toolbar color="primary" dark flat>
          <v-toolbar-title>{{editMode? 'Edit User' : 'Create New User'}}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-progress-circular v-if="processing" indeterminate dark />
        </v-toolbar>

        <v-card-text>
          <v-text-field
            label="E-mail"
            id="email"
            name="email"
            ref="email"
            prepend-icon="mdi-at"
            type="email"
            v-model="local_user.email"
            :readonly="editMode"
            :rules="[rules.required, rules.email]"
            validate-on-blur
            hint="This e-mail address will be used for login."
            :persistent-hint="true"
          ></v-text-field>

          <v-text-field
            label="Name"
            id="name"
            name="name"
            ref="name"
            prepend-icon="mdi-account"
            type="text"
            v-model="local_user.name"
            :rules="[rules.required]"
            validate-on-blur
          ></v-text-field>

          <v-text-field
            id="password"
            label="Password"
            name="password"
            ref="password"
            prepend-icon="mdi-lock"
            type="password"
            v-model="local_user.password"
            :rules="editMode ?  [rules.password] : [rules.required, rules.password]"
            validate-on-blur
            :hint="editMode?'Leave blank if you do not want to change password' : null"
            :persistent-hint="true"
          ></v-text-field>

          <v-text-field
            id="confirmPassword"
            label="Confirm Password"
            name="confirmPassword"
            v-model="local_user.confirmPassword"
            prepend-icon="mdi-lock"
            type="password"
            :rules="editMode? [rules.password, rules.confirm] : [rules.required, rules.password, rules.confirm]"
            validate-on-blur
          ></v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn v-if="editMode" text color="primary" @click.stop="updateUser">Update</v-btn>
          <v-btn v-else text color="primary" @click.stop="createUser">Create</v-btn>
          <v-btn text @click.stop="close">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-form>
  </v-dialog>
</template> 

<script>
export default {
  name: "UserModal",
  props: {
    value: Boolean,
    editMode: Boolean,
    user: Object
  },
  data() {
    return {
      processing: false,
      local_user: this.user || {
        id: null,
        name: null,
        email: null,
        password: null
      },
      confirmPassword: null,
      rules: {
        required: v => !!v || "Required.",
        email: v => /.+@.+\..+/.test(v) || "E-mail must be valid",
        password: v => {
          //In edit mode, user can leave password field blank if he doesn't want to change password
          if (this.editMode && (v == null || v == "")) return true;

          const pattern = /^[\w\d]{6,15}$/;
          return (
            pattern.test(v) ||
            "Password must be 6 to 15 characters long. Alphabet and digits only."
          );
        },
        confirm: (v) => {
          if (this.editMode && (v == null || v == "")) return true;
          this.local_user.password === this.local_user.confirmPassword ||
          "Password and Confirm Password must match";
        }
      }
    };
  },
  computed: {
    show: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("input", value);
      }
    }
  },

  watch: {
    show: function(val) {
      this.processing = false;

      if (val) {
        this.local_user = JSON.parse(JSON.stringify(this.user)); //create a copy
        this.local_user.confirmPassword = this.user.password;

        requestAnimationFrame(() => {
          if (this.editMode) 
            this.$refs.name.focus();
          else 
            this.$refs.email.focus();

          this.$refs.form.resetValidation();
        });
      }
    }
  },

  methods: {
    createUser() {
      if (this.$refs.form.validate()) {
        this.processing = true;

        this.$store
          .dispatch("createUser", this.local_user)
          .then(() => {
            this.processing = false;
            this.show = false;
          })
          .catch(err => {
            this.$root.$confirm.openErr(err);
            this.processing = false;
          });
      }
    },

    updateUser() {
      if (this.$refs.form.validate()) {
        this.processing = true;

        this.$store
          .dispatch("updateUser", this.local_user)
          .then(() => {
            this.processing = false;
            this.show = false;
          })
          .catch(err => {
            this.$root.$confirm.openErr(err);
            this.processing = false;
          });
      }
    },

    close() {
      this.$refs.form.reset();
      this.show = false;
    }
  }
};
</script>