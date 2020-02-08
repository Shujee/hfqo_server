<template>
  <v-dialog v-model="show" :max-width="850">
    <v-card class="elevation-12">
      <v-toolbar color="primary" dark flat>
        <v-toolbar-title>Master File Permissions</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-progress-circular v-if="processing" indeterminate dark />
      </v-toolbar>

      <v-card-text class="mt-2">
        <v-data-table
          :headers="headers"
          :items="accesses"
          class="elevation-1 ma-0"
          :items-per-page="25"
          :footer-props="{
              'items-per-page-options': [10,25,50]
            }"
          :sort-by="['updated_at']"
          :sort-desc="[true]"
        >
          <template v-slot:item.user_name="{ item }">
            <v-select
              v-if="item.added"
              :items="users"
              v-model="item.user_id"
              item-text="name"
              item-value="id"
            />
            <span>{{ item.user_name }}</span>
          </template>

          <template v-slot:item.start="{ item }">
            <date-picker-icon v-model="item.start" />
          </template>

          <template v-slot:item.end="{ item }">
            <date-picker-icon v-model="item.end" />
          </template>

          <template v-slot:item.updated_at="{ item }">
            <v-layout justify-center>
              <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                  <v-chip success outlined ml-3 v-on="on">
                    <v-icon left outline mr-2>mdi-clock</v-icon>
                    {{item.age}}
                  </v-chip>
                </template>
                <span>{{ item.updated_at === null ? "New" : new Date(item.updated_at).toString()}}</span>
              </v-tooltip>
            </v-layout>
          </template>

          <template v-slot:item.actions="{ item }">
            <v-checkbox
              color="red"
              class="align-center justify-center hide-details"
              v-model="item.deleted"
            ></v-checkbox>
          </template>
        </v-data-table>

        <v-overlay :value="loading">
          <v-progress-circular color="primary" indeterminate dark />
        </v-overlay>
      </v-card-text>

      <v-card-actions>
        <v-btn text color="primary" class="ml-2" @click.stop="addAccess">Allow New User</v-btn>
        <v-spacer></v-spacer>
        <v-btn text color="primary" @click.stop="updateAccesses">Update</v-btn>
        <v-btn text @click.stop="close">Cancel</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template> 

<script>
import DatePickerIcon from "./DatePickerIcon";
import { mapGetters } from "vuex";
import moment from "moment";

export default {
  name: "ExamAccessModal",
  components: {
    DatePickerIcon
  },

  props: {
    value: Boolean,
    exam: Object
  },

  data() {
    return {
      processing: false,
      loading: false,
      accesses: [],
      headers: [
        { text: "User Name", value: "user_name", sortable: false, width: 200 },
        { text: "Start", value: "start", sortable: false, width: 150 },
        { text: "End", value: "end", sortable: false, width: 150 },
        {
          text: "Last Updated",
          value: "updated_at",
          align: "center",
          sortable: false,
          width: 150,
        },
        { text: "Delete", value: "actions", sortable: false, width: 100 }
      ]
    };
  },

  computed: {
    ...mapGetters(["users"]),
    show: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("input", value);
      }
    }
  },

  mounted() {
    //We need users data to show in New User dropdown, so we fetch it here in mounted.
    this.$store.dispatch("fetchUsers");
  },

  watch: {
    show: function(val) {
      this.processing = false;

      if (val) {
        this.loading = true;

        this.$store
          .dispatch("fetchAccesses", this.exam.id)
          .then(data => {
            this.accesses = data.data.data;

            //adding properties to mark new and deleted rows
            for (let index = 0; index < this.accesses.length; index++) {
              this.accesses[index].deleted = false;
              this.accesses[index].added = false;
            }

            this.loading = false;
          })
          .catch(() => {
            this.loading = false;
          });
      }
    }
  },

  methods: {
    addAccess() {
      var StartDate = moment();
      var EndDate = moment();
      EndDate.add(7, "days");

      this.accesses.push({
        added: true,
        deleted: false,
        user_id: null,
        exam_id: this.exam.id,
        start: StartDate.toISOString(),
        end: EndDate.toISOString(),
        updated_at: null,
      });
    },

    updateAccesses() {
      this.processing = true;

      this.$store
        .dispatch("updateAccesses", this.accesses)
        .then(() => {
          this.processing = false;
          this.show = false;
        })
        .catch(err => {
          this.$root.$confirm.openErr(err);
          this.processing = false;
        });
    },

    close() {
      this.show = false;
    }
  }
};
</script>