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
          ></v-autocomplete>
        </v-col>
        <v-col cols="6" sm="3" md="2">
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
                readonly
                v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker v-model="start" @input="menu = false"></v-date-picker>
          </v-menu>
        </v-col>
        <v-col cols="6" sm="3" md="2">
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
                readonly
                v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker v-model="end" @input="menu2 = false"></v-date-picker>
          </v-menu>
        </v-col>
        <v-col cols="12" sm="4" md="3">
           <v-autocomplete
            v-model="location"
            :items="locations"
            :loading="loadingExams"
            label="Location"
            prepend-inner-icon="mdi-pin"
          ></v-autocomplete>
        </v-col>
        <v-col cols="6" sm="3" md="2">
          <v-select :items="frequencies" v-model="frequency" label="Frequency">
            <template v-slot:item="data">
              <v-chip :color="frequencyColor(data.item.value)" text-color="white">{{data.item.text}}</v-chip>
            </template>
            <template v-slot:selection="data">
              <v-chip :color="frequencyColor(data.item.value)" text-color="white">{{data.item.text}}</v-chip>
            </template>
          </v-select>
        </v-col>
        <v-col align="end">
          <v-btn color="primary" @click="refreshTable" fab>
            <v-icon>mdi-file</v-icon>
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
      loading: false,
      loadingExams: false,
      loadingLocations: false,
      frequencies: [
        { text: "3 or more", value: 3 },
        { text: "2 or more", value: 2 },
        { text: "1 or more", value: 1 }
      ],
      locations: [],
      exams: []
    };
  },

  methods: {
    frequencyColor(freq) {
      if (freq > 2) return "green";
      else if (freq > 1) return "orange";
      else return "red";
    },

    refreshTable() {
      this.$refs.HFQDT.fetchReport();
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