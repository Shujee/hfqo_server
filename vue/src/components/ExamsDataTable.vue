<template>
  <v-card class="ma-0 pa-0">
    <v-card-title class="ma-0 py-1">
      Master Files
      <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
      ></v-text-field>
    </v-card-title>

    <v-data-table
      style="height: calc(100vh - 120px); overflow: auto;" 
      full-width
      :headers="headers"
      :items="exams"
      :loading="loading"
      class="elevation-1 ma-0"
      :items-per-page="25"
      :search="search"
      :readonly="true"
      :footer-props="{
        'items-per-page-options': [10,25, 50]
      }">
      <template v-slot:item.name="{ item }">
        <v-avatar size="32">
          <v-icon>mdi-file-document</v-icon>
        </v-avatar>
        <span class="pl-4">{{ item.name }}</span>
      </template>

      <template v-slot:item.xps_file_name="{ item }">
        <span>{{ item.xps_file_name }}</span>
      </template>
      
      <template v-slot:item.xml_file_name="{ item }">
        <span>{{ item.xml_file_name }}</span>
      </template>
      
      <template v-slot:item.qa_count="{ item }">
        <span>{{ item.qa_count }}</span>
      </template>
      
      <template v-slot:item.is_expired="{ item }">
        <v-checkbox :readonly="true" v-model="item.is_expired"></v-checkbox>
      </template>

      <template v-slot:item.created_at="{ item }" class="align-center">
        <v-chip success outlined ml-3>
          <v-icon left outline mr-2>mdi-clock</v-icon>
          {{item.age}}
        </v-chip>
      </template>

      <template v-slot:item.actions="{ item }">
        <v-icon v-if="item.can_update" @click="editItem(item)">mdi-pencil</v-icon>
        <v-icon v-else @click="viewItem(item)">mdi-window-maximize</v-icon>
        <v-icon :disabled="!item.can_cancel" @click="cancelItem(item)">mdi-close</v-icon>
        <v-icon :disabled="!item.can_delete" @click="deleteItem(item)">mdi-delete</v-icon>
      </template>
    </v-data-table>
  </v-card>
</template>
<script>
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      loading: false,
      processing: false,
      search: '',
      headers: [
        { text: "Name", value: "name", sortable: false },
        { text: "XPS File", value: "xps_file_name", sortable: false },
        { text: "XML File", value: "xml_file_name", sortable: false },
        { text: "QA Count", value: "qa_count", align: "center", sortable: false },
        { text: "Expired", value: "is_expired", align: "center", sortable: false },
        { text: "Created On", value: "created_at", align: "end", sortable: false },
        { text: "", value: "actions", sortable: false, align:'end' } 
      ]
    };
  },
  methods: {
    // deleteItem: async function(item) {
    //   if (await this.$root.$confirm.open('Delete', 'Do you want to permanently delete this master file?', { color: 'primary' })) {
    //     this.processing = true;
    //     this.$store.dispatch("deleteExam", item.id)
    //       .then(() => this.processing = false)
    //       .catch(() => this.processing = false);
    //   }
    // },
  },
  computed: {
    ...mapGetters(["exams"])
  },
  mounted() {
    this.loading = true;
    this.$store
      .dispatch("fetchExams")
        .then(() => this.loading = false)
        .catch(() => this.loading = false);
  }
};
</script>
