<template>
  <v-card flat class="ma-4 scroll">
    <v-container fluid row>
      <v-row>
        <v-col v-for="media in images" :key="media.filename" class="d-flex child-flex" cols="1">
          <v-card flat tile class="d-flex">
            <v-img
              :src="media.filename"
              :lazy-src="media.filename"
              aspect-ratio="1"
              class="grey lighten-2"
            >
              <template v-slot:placeholder>
                <v-row class="fill-height ma-0" align="center" justify="center">
                  <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                </v-row>
              </template>
            </v-img>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </v-card>
</template>             
<script>
export default {
  props: {
    images: Array
  }
};
</script>