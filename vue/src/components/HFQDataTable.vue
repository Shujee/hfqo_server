<template>
  <v-data-table
    full-width
    :headers="headers"
    :loading="loading"
    class="elevation-1 ma-0"
    :items-per-page="25"
    :items="items"
    :search="search"
    :readonly="true"
    :footer-props="{
      'items-per-page-options': [10,25,50]
    }"
    :sort-by="['freq', 'a1']"
    :sort-desc="[true]"
  >
    <template v-slot:item.freq="{ item }">
      <v-layout justify-center>
        <v-chip :color="frequencyColor(item.freq)" text-color="white" ml-3>{{item.freq}}</v-chip>
      </v-layout>
    </template>
  </v-data-table>
</template>
<script>
export default {
  props: {
    start: null,
    end: null,
    exam: null,
    location: null,
    frequency: null
  },
  data() {
    return {
      loading: false,
      search: "",
      headers: [
        { text: "Index", value: "index", sortable: false },
        { text: "Question", value: "question", sortable: false },
        { text: "Answer", value: "answer", sortable: false },
        { text: "Frequency", value: "freq", sortable: false }
      ],
      items: []
    };
  },

  methods: {
    fetchReport() {
      this.loading = true;
      this.$store
        .dispatch("fetchHFQReport", {
          exam: this.exam,
          start: this.start,
          end: this.end,
          location: this.location,
          frequency: this.frequency
        })
        .then(response => {
          this.loading = false;
          this.items = response.data;
        })
        .catch(() => (this.loading = false));
    },

    frequencyColor(freq) {
      if (freq > 2) return "green";
      else if (freq > 1) return "orange";
      else return "red";
    },
  }
};
</script>