<template>
  <v-container class="fill-height" fluid>
    <v-row align="center" justify="center">
      <v-col cols="12" sm="8" md="4" justify-center>
        <div class="flex d-flex justify-center display-4 font-weight-regular text-center">HFQ Server</div>
        <br />
        <div class="flex d-flex justify-center display-2 font-weight-thin text-center">Version {{ version }}</div>
        <br />
        <div class="flex d-flex justify-center display-1 font-weight-thin text-center">Commit Hash: {{ commit_hash }}</div>
        <br />
        <div class="flex d-flex justify-center display-1 font-weight-thin text-center">Commit Date: {{ commit_date }} UTC</div>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
export default {
  data() {
    return {
      version: '',
      commit_hash: '',
      commit_date: '',
    }
  },

  mounted() {
      this.$store
        .dispatch("fetchGitVersion")
        .then((res) => {
          this.version = res.version;
          this.commit_hash = res.commit_hash;
          this.commit_date = res.commit_date;
        })
        .catch(() => (this.gitVersion = 'Not Found'));    
  },
}
</script>