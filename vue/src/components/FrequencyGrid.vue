<template>
  <div style="height: calc(100vh - 64px); overflow: auto;">
    <v-card class="ma-0 pa-0">
      <v-card-title class="ma-0 py-1">
        <v-icon class="mr-2">mdi-grid</v-icon>
        <span>Frequency Grid</span>
      </v-card-title>

      <v-toolbar flat>
        <v-row class="justify-center">
          <v-col cols="12" sm="6">
            <v-autocomplete
              v-model="exam"
              :items="exams"
              :loading="loadingExams"
              item-text="name"
              item-value="id"
              label="Master File"
              prepend-inner-icon="mdi-file-outline"
              @change="FetchExamMetaData()"
            ></v-autocomplete>
          </v-col>
        </v-row>
      </v-toolbar>
    </v-card>

    <v-row>
      <v-col cols="8">
        <v-row class="justify-center">
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-btn class="ma-2" tile outlined color="primary" v-on="on" @click="addRow(null)">
                <v-icon left>mdi-plus</v-icon>Add Row
              </v-btn>

              <v-btn
                class="ma-2"
                tile
                color="primary"
                :loading="uploading"
                :disabled="uploading"
                v-on="on"
                @click="save()"
              >
                <v-icon left>mdi-upload</v-icon>Upload
              </v-btn>
            </template>
            <span>Submits frequency data for the selected exam to the server.</span>
          </v-tooltip>
        </v-row>
        <v-row class="justify-center">
          <v-simple-table fixed-header>
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-center title">Index</th>
                  <th class="text-center title">A1</th>
                  <th class="text-center title">A2</th>
                  <th class="text-center title">A3</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, i) in uploadrows" :key="item.index">
                  <td class="text-center">{{i +1}}</td>
                  <td>
                    <qa-number-field
                      v-model="item.a1"
                      ref="a1"
                      :i="i"
                      :item="item"
                      :col="1"
                      :uploadrows="uploadrows"
                      :qa_count="exam_meta.qa_count"
                      @keydown_enter="addRow(i)"
                    />
                  </td>
                  <td>
                    <qa-number-field
                      v-model="item.a2"
                      ref="a2"
                      :i="i"
                      :item="item"
                      :col="2"
                      :uploadrows="uploadrows"
                      :qa_count="exam_meta.qa_count"
                      @keydown_enter="addRow(i)"
                    />
                  </td>
                  <td>
                    <qa-number-field
                      v-model="item.a3"
                      ref="a3"
                      :i="i"
                      :item="item"
                      :col="3"
                      :uploadrows="uploadrows"
                      :qa_count="exam_meta.qa_count"
                      @keydown_enter="addRow(i)"
                    />
                  </td>
                  <td class="text-right">
                    <v-icon color="red" @click="deleteRow(i)">mdi-close-circle</v-icon>
                  </td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
        </v-row>
      </v-col>

      <v-col cols="4">
        <v-card class="mx-auto" max-width="344" :loading="fetchingExam">
          <v-card-text>
            <div>Exam Information</div>
            <p class="display-1">{{exam_meta.name}}</p>

            <v-list>
              <v-list-item-group>
                <v-list-item>
                  <v-list-item-icon>
                    <v-icon>mdi-pound</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                    <v-list-item-title>Exam Number: {{exam_meta!=null ? exam_meta.number : ''}}</v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
                <v-list-item>
                  <v-list-item-icon>
                    <v-icon>mdi-format-list-numbered</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                    <v-list-item-title>QA Count: {{exam_meta!=null ? exam_meta.qa_count : ''}}</v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
                <v-list-item>
                  <v-list-item-icon>
                    <v-icon>mdi-calendar</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                    <v-list-item-title>Last Updated: {{exam_meta!=null && exam_meta.updated_at != null ? exam_meta.updated_at.slice(0,10) : ''}}</v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
                <v-list-item>
                  <v-list-item-icon>
                    <v-icon>mdi-comment</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                    <v-list-item-title>Last Action: {{exam_meta!=null ? exam_meta.remarks : ''}}</v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
                <v-list-item>
                  <v-list-item-icon>
                    <v-icon>mdi-account</v-icon>
                  </v-list-item-icon>
                  <v-list-item-content>
                    <v-list-item-title>Uploader: {{exam_meta!=null ? exam_meta.uploader : ''}}</v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </v-list-item-group>
            </v-list>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import QaNumberField from "../components/QANumberField";

export default {
  components: {
    QaNumberField
  },

  data() {
    return {
      exam: null,
      loadingExams: false,
      exams: [],
      uploadrows: [],
      uploading: false,
      fetchingExam: false,
      exam_meta: {}
    };
  },

  methods: {
    FetchExamMetaData: function() {
      console.log('FetchExamMetaData');
      if (this.exam != null) {
        this.fetchingExam = true;
        this.$store.dispatch("fetchExam", this.exam).then(response => {
          this.fetchingExam = false;
          this.exam_meta = response.data;
        }).catch(() => this.fetchingExam = false);
      }
    },

    addRow: function(i) {
      if (
        this.uploadrows.length == 0 ||
        this.uploadrows[this.uploadrows.length - 1].a1 != null
      ) {
        this.uploadrows.push({
          a1: null,
          a2: null,
          a3: null
        });
      }

      if (i == null) {
        this.$nextTick(() => {
          this.$refs.a1[this.uploadrows.length - 1].focus();
        });
      } else if (i < this.uploadrows.length - 1) {
        this.$nextTick(() => {
          this.$refs.a1[i + 1].focus();
        });
      }
    },

    deleteRow: function(i) {
      this.uploadrows.splice(i, 1);
    },

    save: function() {
      if (this.exam == null) {
        this.$root.$confirm.open(
          "Frequency Grid",
          "Please select a master file from the dropdown first.",
          { color: "error", show_cancel: false }
        );
      } else if (
        this.uploadrows == null ||
        this.uploadrows.length == 0 ||
        (this.uploadrows.length == 1 &&
          this.uploadrows[0].a1 == null &&
          this.uploadrows[0].a2 == null &&
          this.uploadrows[0].a3 == null)
      ) {
        this.$root.$confirm.open(
          "Frequency Grid",
          "There is no data to submit.",
          { color: "error", show_cancel: false }
        );
      } else if (this.uploadrows.find(e => e.a1 == null)) {
        this.$root.$confirm.open(
          "Frequency Grid",
          "Column A1 cannot be null for any row.",
          { color: "error", show_cancel: false }
        );
      } else {
        this.uploading = true;

        var Result = this.uploadrows.map((r, i) => ({ ...r, q: i + 1 }));

        this.$store
          .dispatch("uploadFrequency", {
            freq: Result,
            machine_name: "SERVER",
            exam_id: this.exam
          })
          .then(() => {
            this.uploading = false;
            this.uploadrows = [
              {
                a1: null,
                a2: null,
                a3: null
              }
            ];

            this.$root.$confirm.open(
              "Frequency Grid",
              "Successfully uploaded data to the server.",
              { color: "success", show_cancel: false }
            );
          })
          .catch(err => {
            this.uploading = false;
            this.$root.$confirm.openErr(err);
          });
      }
    }
  },

  mounted() {
    this.uploadrows.push({ a1: null, a2: null, a3: null });

    this.loadingExams = true;
    this.$store
      .dispatch("fetchExamNames")
      .then(response => {
        this.loadingExams = false;
        this.exams = response.data;
      })
      .catch(() => (this.loadingExams = false));
  }
};
</script>
<style>
.centered-input input {
  text-align: center;
}
</style>