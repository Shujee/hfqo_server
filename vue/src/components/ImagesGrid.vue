<template>
  <v-card flat class="ma-4">
    <v-col cols="12">
      <v-card>
        <v-container fluid>
          <v-row>
            <v-col v-for="media in images" :key="media.filename" class="d-flex child-flex" cols="2">
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
    </v-col>
  </v-card>
</template>             
<script>
export default {
  props: {
    images: Array
  }
};
</script>