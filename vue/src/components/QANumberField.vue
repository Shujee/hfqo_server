<template>
  <v-text-field
    type="number"
    class="centered-input"
    @change="Validate()"
    ref="TXT"
    @keydown.native.enter="RaiseKeyDown"
    v-bind:value="value"
    v-on:input="$emit('input', $event)"
  />
</template>
<script>
export default {
  data() {
    return {};
  },

  props: {
    i: Number,
    item: Object,
    col: Number,
    uploadrows: Array,
    qa_count: Number,
    value: String
  },

  methods: {
    Validate: function() {
      var T = this.$refs.TXT;
      var err = this.ValidateInternal(T);

      if (err == 1) {
        this.$root.$confirm
          .open(
            "Frequency Grid",
            "This value is not valid. Value must be an integer between 1 and " +
              this.qa_count +
              ".",
            { color: "error", show_cancel: false }
          )
          .then(() => this.$nextTick(() => T.focus()));
      } else if (err == 2) {
        this.$root.$confirm
          .open(
            "Frequency Grid",
            "You have already used this value in another place. Value must be unique.",
            { color: "error", show_cancel: false }
          )
          .then(() => this.$nextTick(() => T.focus()));
      }
    },

    ValidateInternal: function(T) {
      var v = T.value;

      if (!v) return 0;

      if (isNaN(v) || v < 0 || v > this.qa_count) {
        return 1;
      }

      var Existing = null;

      switch (this.col) {
        case 1:
          Existing = this.uploadrows.find(
            e =>
              (e !== this.item && (e.a1 == v || e.a2 == v || e.a3 == v)) ||
              (e === this.item && (e.a2 == v || e.a3 == v))
          );
          break;
        case 2:
          Existing = this.uploadrows.find(
            e =>
              (e !== this.item && (e.a1 == v || e.a2 == v || e.a3 == v)) ||
              (e === this.item && (e.a1 == v || e.a3 == v))
          );
          break;
        case 3:
          Existing = this.uploadrows.find(
            e =>
              (e !== this.item && (e.a1 == v || e.a2 == v || e.a3 == v)) ||
              (e === this.item && (e.a1 == v || e.a2 == v))
          );
          break;
      }

      if (Existing) {
        return 2;
      }

      return 0;
    },

    RaiseKeyDown: function(e) {
      if(this.ValidateInternal(this.$refs.TXT) == 0)
        this.$emit("keydown_enter", e);
    },

    focus() {
      this.$refs.TXT.focus();
    }
  }
};
</script>