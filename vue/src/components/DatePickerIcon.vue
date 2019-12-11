<template>
  <v-menu
    v-model="menu"
    :close-on-content-click="false"
    :nudge-right="40"
    transition="scale-transition"
    offset-y
    min-width="290px"
  >
    <template v-slot:activator="{ on }">
      <v-text-field v-model="date" prepend-icon="mdi-calendar" readonly v-on="on"></v-text-field>
    </template>
    <v-date-picker v-model="date" @input="menu = false"></v-date-picker>
  </v-menu>
</template>

<script>
export default {
  props: {
    value: String
  },
  data() {
    return {
      menu: false,
      date: this.value == null ? null : this.value.substr(0, 10)
    };
  },

  watch: {
    value: function(val) {
      this.date = val.substring(0, 10);
    },
    date: function(val) {
      this.$emit("input", val);
    }
  }
};
</script>