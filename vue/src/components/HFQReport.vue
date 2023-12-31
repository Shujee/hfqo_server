<template>
  <div style="height: calc(100vh - 64px); overflow: auto;">
    <v-card class="ma-0 pa-0">
      <v-card-title class="ma-0 py-1">
        <v-icon class="mr-2">mdi-file-chart</v-icon>
        <span>HFQ Report</span>
      </v-card-title>

      <v-toolbar flat>
        <v-col cols="12" sm="4" md="2">
          <v-autocomplete
            v-model="exam"
            :items="exams"
            :loading="loadingExams"
            item-text="name"
            item-value="id"
            label="Master File"
            prepend-inner-icon="mdi-file-outline"
            @change="fetchUploadDates"
          ></v-autocomplete>
        </v-col>
        <v-col cols="6" sm="4" md="2">
          <v-menu
            v-model="menu"
            :close-on-content-click="false"
            :nudge-right="40"
            transition="scale-transition"
            offset-y
            min-width="290px"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                v-model="start"
                label="Start Date"
                prepend-inner-icon="mdi-calendar"
                clearable
                readonly
                v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker
              v-model="start"
              @input="menu = false"
              :events="upload_dates"
              event-color="red lighten-1"
            ></v-date-picker>
          </v-menu>
        </v-col>
        <v-col cols="6" sm="4" md="2">
          <v-menu
            v-model="menu2"
            :close-on-content-click="false"
            :nudge-right="40"
            transition="scale-transition"
            offset-y
            min-width="290px"
          >
            <template v-slot:activator="{ on }">
              <v-text-field
                v-model="end"
                label="End Date"
                prepend-inner-icon="mdi-calendar"
                clearable
                readonly
                v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker
              v-model="end"
              @input="menu2 = false"
              :events="upload_dates"
              event-color="red lighten-1"
            ></v-date-picker>
          </v-menu>
        </v-col>
        <v-col cols="12" sm="4" md="2">
          <v-autocomplete
            clearable
            v-model="location"
            :items="locations"
            :loading="loadingExams"
            label="Location"
            prepend-inner-icon="mdi-pin"
          ></v-autocomplete>
        </v-col>
        <v-col cols="6" sm="4" md="2">
          <v-select :items="frequencies" v-model="frequency" label="Frequency">
            <template v-slot:item="data">
              <v-chip
                :color="frequencyColor(data.item.value)"
                text-color="white"
                class="mr-4 px-2 py-0"
              >
                <v-icon>mdi-chevron-right</v-icon>
              </v-chip>
              {{data.item.text}}
            </template>
            <template v-slot:selection="data">{{data.item.text}}</template>
          </v-select>
        </v-col>
        <v-col cols="6" sm="2" md="2" align="end">
          <v-btn color="primary" class="mr-2" @click="refreshTable" fab>
            <v-icon>mdi-refresh</v-icon>
          </v-btn>
          <v-btn color="primary" :loading="loadingDownload" @click="refreshAndDownloadTable" fab>
            <v-icon>mdi-file-download</v-icon>
          </v-btn>
        </v-col>
      </v-toolbar>

      <HFQDataTable
        ref="HFQDT"
        :start="start"
        :end="end"
        :exam="exam"
        :location="location"
        :frequency="frequency"
      />
    </v-card>
  </div>
</template>

<script>
import HFQDataTable from "../components/HFQDataTable";

export default {
  components: {
    HFQDataTable
  },
  data() {
    return {
      menu: false,
      menu2: false,
      start: null,
      end: null,
      exam: null,
      location: null,
      frequency: null,
      upload_dates: [],
      loading: false,
      loadingExams: false,
      loadingLocations: false,
      loadingDownload: false,
      frequencies: [
        { text: "3 or more times", value: 3 },
        { text: "2 times", value: 2 },
        { text: "1 time", value: 1 },
        { text: "All", value: 0 }
      ],
      locations: [],
      exams: []
    };
  },

  methods: {
    frequencyColor(freq) {
      if (freq > 2) return "green";
      else if (freq > 1) return "orange";
      else if (freq > 0) return "red";
      else return "black";
    },

    refreshTable() {
      this.$refs.HFQDT.fetchReport();
    },

    refreshAndDownloadTable() {
      this.$refs.HFQDT.fetchReport();

      this.loadingDownload = true;
      this.$store
        .dispatch("downloadHFQReport", {
          exam: this.exam,
          start: this.start,
          end: this.end,
          location: this.location,
          frequency: this.frequency
        })
        .then(response => {
          this.loadingDownload = false;

          let blob = new Blob([response.data], { type: "text/csv" });
          let link = document.createElement("a");
          link.href = window.URL.createObjectURL(blob);
          link.download = "hfqreport.csv";
          link.click();
        })
        .catch(err => {
          this.loadingDownload = false;
          this.$root.$confirm.openErr(err);
        });
    },

    fetchUploadDates() {
      this.$store
        .dispatch("fetchUploadDates", this.exam)
        .then(response => {
          this.upload_dates = response.data;
        })
        .catch(err => this.$root.$confirm.openErr(err));
    }
  },

  mounted() {
    this.loadingLocations = true;
    this.$store
      .dispatch("fetchUploadLocations")
      .then(response => {
        this.loadingLocations = false;
        this.locations = response.data.map(x => {
          return {
            text: x.city + ", " + x.country,
            value: x
          };
        });
      })
      .catch(() => (this.loadingLocations = false));

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