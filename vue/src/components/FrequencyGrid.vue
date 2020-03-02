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
            ></v-autocomplete>
          </v-col>
        </v-row>
      </v-toolbar>
    </v-card>

    <v-row class="justify-center">
      <v-col cols="12" sm="6">
        <v-row class="justify-end">
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
        <v-row>
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
                    <v-text-field
                      v-model="item.a1"
                      ref="a1"
                      class="centered-input"
                      @keydown.enter="addRow(i)"
                      @change="checkDup(item, 1)"
                    />
                  </td>
                  <td>
                    <v-text-field
                      v-model="item.a2"
                      ref="a2"
                      class="centered-input"
                      @keydown.enter="addRow(i)"
                      @change="checkDup(item, 2)"
                    />
                  </td>
                  <td>
                    <v-text-field
                      v-model="item.a3"
                      ref="a3"
                      class="centered-input"
                      @keydown.enter="addRow(i)"
                      @change="checkDup(item, 3)"
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
    </v-row>
  </div>
</template>

<script>
export default {
  data() {
    return {
      exam: null,
      loadingExams: false,
      exams: [],
      uploadrows: [],
      uploading: false
    };
  },

  methods: {
    checkDup: function(item, col) {
      var Existing = null;
      var v = null;

      switch (col) {
        case 1:
          v = item.a1;
          if (!v) return;
          Existing = this.uploadrows.find(
            e =>
              (e !== item && (e.a1 == v || e.a2 == v || e.a3 == v)) ||
              (e === item && (e.a2 == v || e.a3 == v))
          );
          break;
        case 2:
          v = item.a2;
          if (!v) return;
          Existing = this.uploadrows.find(
            e =>
              (e !== item && (e.a1 == v || e.a2 == v || e.a3 == v)) ||
              (e === item && (e.a1 == v || e.a3 == v))
          );
          break;
        case 3:
          v = item.a3;
          if (!v) return;
          Existing = this.uploadrows.find(
            e =>
              (e !== item && (e.a1 == v || e.a2 == v || e.a3 == v)) ||
              (e === item && (e.a1 == v || e.a2 == v))
          );
          break;
      }

      if (Existing) {
        this.$root.$confirm.open(
          "Frequency Grid",
          "You have already used this value in another place. Value must be unique.",
          { color: "error", show_cancel: false }
        );

        switch (col) {
          case 1:
            item.a1 = null;
            break;
          case 2:
            item.a2 = null;
            break;
          case 3:
            item.a3 = null;
            break;
        }
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
      } 
      else if (
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
      } 
      else if (this.uploadrows.find(e => e.a1 == null))
      {
        this.$root.$confirm.open(
          "Frequency Grid",
          "Column A1 cannot be null for any row.",
          { color: "error", show_cancel: false }
        );
      } 
      else {
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