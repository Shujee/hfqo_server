<template>
  <v-data-table
    :headers="computedHeaders"
    class="elevation-1 px-0 mx-0"
    :items-per-page="10"
    :items="activities"
    :search="search"
    :readonly="true"
    :footer-props="{
    'items-per-page-options': [10,25, 50]
    }"
    :sort-desc="true"
    :caption="userName + ' activities'"
    sort-by="updated_at"
    full-width
  >
    <template v-slot:item.ip="{ item }">
      <span class="text-mono">{{ item.ip }}</span>
    </template>

    <template v-slot:item.action="{ item }">
      <v-chip
        outlined
        class="my-2"
        :color="type2color(item.type)"
        :text-color="type2color(item.type)"
      >
        <v-avatar left>
          <v-icon>{{ type2icon(item.type) }}</v-icon>
        </v-avatar>
        {{ type2text(item.type) }}
      </v-chip>
    </template>

    <template v-slot:item.updated_at="{ item }">
      <v-layout justify-center>
        <v-chip success outlined ml-3>
          <v-icon left outline mr-2>mdi-clock</v-icon>
          {{item.updated_at}}
        </v-chip>
      </v-layout>
    </template>
  </v-data-table>
</template>

<script>
export default {
  props: {
    activities: Array,
    userName: String,
    userType: Number
  },
  data() {
    return {
      search: "",
      headers: [
        { text: "Number", value: "number", sortable: false },
        { text: "Name", value: "name", sortable: false },
        {
          text: "QA Count",
          value: "qa_count",
          align: "center",
          sortable: false
        },
        {
          text: "IP",
          value: "ip"
        },
        {
          text: "City",
          value: "city",
          visible: false
        },
        {
          text: "Country",
          value: "country"
        },
        {
          text: "Action",
          value: "action",
          align: "center"
        },
        {
          text: "Activity Date",
          value: "updated_at",
          align: "center",
          sortable: false
        }
      ]
    };
  },
  computed: {
    computedHeaders() {
      if (this.userType == 2) {
        //do not show ip, country and city columns for uploader
        var cols = this.headers.filter(
          e => e.value !== "ip" && e.value !== "country" && e.value !== "city"
        );
        return cols;
      }
      //vm.$recompute('key'
      else {
        return this.headers;
      }
    }
  },
  methods: {
    type2color: function(type) {
      if (type === "R") return "blue";
      else if (type === "D") return "green";
      else if (type === "U") return "red";
    },
    type2icon: function(type) {
      if (type === "R") return "mdi-arrow-up-bold-circle";
      else if (type === "D") return "mdi-arrow-down-bold-circle";
      else if (type === "U") return "mdi-arrow-up-bold-circle";
    },
    type2text: function(type) {
      if (type === "R") return "Result Uploaded";
      else if (type === "D") return "Exam Downloaded";
      else if (type === "U") return "Exam Uploaded";
    }
  },

  watch: {
    userType: {}
  }
};
</script>