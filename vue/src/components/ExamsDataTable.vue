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
      }"
      :sort-by="['updated_at']"
      :sort-desc="[true]"
    >
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
        <v-checkbox color="error" :readonly="true" v-model="item.is_expired"></v-checkbox>
      </template>

      <template v-slot:item.updated_at="{ item }">
        <v-layout justify-center>
          <v-chip success outlined ml-3>
            <v-icon left outline mr-2>mdi-clock</v-icon>
            {{item.age}}
          </v-chip>
        </v-layout>
      </template>

      <template v-slot:item.actions="{ item }">
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
            <v-icon @click="expireExam(item)" class="mr-2" v-on="on">mdi-calendar-remove</v-icon>
          </template>
          <span>Toggle expired status of this master file</span>
        </v-tooltip>

        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
            <v-icon @click="deleteExam(item)" v-on="on">mdi-delete</v-icon>
          </template>
          <span>Delete this master file</span>
        </v-tooltip>
      </template>
    </v-data-table>

    <v-overlay :value="processing">
      <v-progress-circular color="primary" indeterminate dark />
    </v-overlay>
  </v-card>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      loading: false,
      processing: false,
      search: "",
      headers: [
        { text: "Name", value: "name", sortable: false },
        { text: "XPS File", value: "xps_file_name", sortable: false },
        { text: "XML File", value: "xml_file_name", sortable: false },
        {
          text: "QA Count",
          value: "qa_count",
          align: "center",
          sortable: false
        },
        {
          text: "Expired",
          value: "is_expired",
          align: "left",
          sortable: false
        },
        {
          text: "Last Updated",
          value: "updated_at",
          align: "center",
          sortable: false
        },
        { text: "", value: "actions", sortable: false, align: "end" }
      ]
    };
  },
  methods: {
    deleteExam: async function(exam) {
      if (
        await this.$root.$confirm.open(
          "Delete",
          "Do you want to delete this master file?",
          { color: "primary" }
        )
      ) {
        this.processing = true;
        this.$store
          .dispatch("deleteExam", exam.id)
          .then(() => (this.processing = false))
          .catch(() => (this.processing = false));
      }
    },

    expireExam: async function(exam) {
      this.processing = true;

      exam.is_expired = !exam.is_expired;
      this.$store
        .dispatch("updateExam", exam)
        .then(() => {
          this.processing = false;
        })
        .catch(() => {
          this.processing = false;
          exam.is_expired = !exam.is_expired; //restore local value if server update fails.
        });
    }
  },
  computed: {
    ...mapGetters(["exams"])
  },
  mounted() {
    this.loading = true;
    this.$store
      .dispatch("fetchExams")
      .then(() => (this.loading = false))
      .catch(() => (this.loading = false));
  }
};
</script>
