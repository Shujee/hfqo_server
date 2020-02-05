<template>
  <v-data-table
    :headers="headers"
    class="elevation-1 px-0 mx-0"
    :items-per-page="10"
    :items="activities"
    :search="search"
    :readonly="true"
    :footer-props="{
    'items-per-page-options': [10,25, 50]
    }"
    :sort-desc="true"
    :caption="username + ' activities'"
    sort-by="updated_at"
    full-width
  >
    <template v-slot:item.number="{ item }">
      <div class="ellipsis">
        <v-icon :color="type2color(item.type)">{{ type2icon(item.type) }}</v-icon>
        <span class="pl-4 ellipsis">{{ item.number }}</span>
      </div>
    </template>

    <template v-slot:item.is_expired="{ item }">
      <v-icon v-if="item.is_expired" color="red">mdi-checkbox-marked</v-icon>
      <v-icon v-else>mdi-checkbox-blank-outline</v-icon>
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
    username: String,
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
          text: "Expired",
          value: "is_expired",
          align: "center",
          sortable: false
        },
        {
          text: "Activity Date",
          value: "updated_at",
          align: "center",
          sortable: false
        },
      ]
    };
  },
  methods: {
      type2color: function(type) {
        if(type === 'R')
            return 'blue';
        else if(type === 'D')
            return 'green';
        else if(type === 'U')
            return 'red';
      },
      type2icon: function(type) {
        if(type === 'R')
            return 'mdi-arrow-up-bold';
        else if(type === 'D')
            return 'mdi-arrow-down-bold';
        else if(type === 'U')
            return 'mdi-arrow-up-bold';
      },
  },
};
</script>