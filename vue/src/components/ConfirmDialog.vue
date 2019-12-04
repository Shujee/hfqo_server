<template>
  <v-dialog
    v-model="show"
    :max-width="options.width"
    :style="{ zIndex: options.zIndex }"
    @keydown.esc="cancel"
  >
    <v-card>
      <v-toolbar dark :color="options.color" dense flat>
        <v-toolbar-title class="white--text">{{ title }}</v-toolbar-title>
      </v-toolbar>
      <v-layout wrap>
        <v-flex xs2 right>
          <v-icon large class="pa-4" :color="options.color">{{ options.icon }}</v-icon>
        </v-flex>
        <v-flex xs10>
          <v-card-text v-show="!!message" class="pa-4">
            <span v-if="message !== Object(message)">{{ message }}</span>
            <template v-else>
              <span v-if="Object.keys(message).length == 1">{{ Object.values(message)[0][0] }}</span>
              <ul v-else>
                <template v-for="k in message">
                    <li v-for="s in k" :key="s">{{ s }}</li>
                </template>
              </ul>
            </template>
          </v-card-text>
        </v-flex>
      </v-layout>

      <v-card-actions class="pt-0">
        <v-spacer></v-spacer>
        <v-btn color="primary darken-1" text @click.native="agree">OK</v-btn>
        <v-btn v-show="options.show_cancel" color="grey" text @click.native="cancel">Cancel</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  data: () => ({
    show: false,
    resolve: null,
    reject: null,
    message: null,
    title: null,
    options: {
      color: "primary",
      icon: "mdi-help-circle",
      width: 350,
      zIndex: 200,
      show_cancel: true
    }
  }),
  methods: {
    open(title, message, options) {
      this.show = true;
      this.title = title;
      this.message = message;
      this.options = Object.assign(this.options, options);
      return new Promise((resolve, reject) => {
        this.resolve = resolve;
        this.reject = reject;
      });
    },
    agree() {
      this.resolve(true);
      this.show = false;
    },
    cancel() {
      this.resolve(false);
      this.show = false;
    }
  }
};
</script>