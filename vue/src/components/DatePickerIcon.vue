<template>
  <v-menu
    ref="menu"
    v-model="menu"
    :close-on-content-click="false"
    :return-value.sync="value"
    transition="scale-transition"
    offset-y
    min-width="290px"
  >
    <template v-slot:activator="{ on }">
      <v-icon>mdi-clock</v-icon>
    </template>
    <v-date-picker v-model="value" no-title scrollable>
      <v-spacer></v-spacer>
      <v-btn text color="primary" @click="saveDate">OK</v-btn>
      <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
    </v-date-picker>
  </v-menu>
</template>as

<script>
export default {
  props: {
    value: Boolean
  },
  data() {
    return {
      menu: false
    };
  },

  methods: {
    saveDate(){
      //$refs.menu.save(this.value);
      this.$emit("input", this.value);
    }
  },
};
</script>