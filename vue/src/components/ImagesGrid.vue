<template>
  <v-card class="ma-4">
    <v-container fluid>
      <v-row>
        <v-col v-for="img in images" :key="img.name" class="d-flex child-flex" cols="2">
          <v-card>
            <v-col cols="12">
              <v-hover v-slot:default="{ hover }">
                <v-img contain width="auto" :src="img.thumb" @click="showLightbox(img.name)">
                  <template v-slot:placeholder>
                    <v-row class="fill-height ma-0" align="center" justify="center">
                      <v-progress-circular indeterminate color="grey"></v-progress-circular>
                    </v-row>
                  </template>
                  <v-expand-transition >
                    <div
                      v-if="hover"
                      class="d-flex transition-fast-in-fast-out white darken-2 v-card--reveal"
                      style="height: 100%;"
                    >
                      
                      <v-icon color="black" large>mdi-magnify-plus</v-icon>

                    </div>
                  </v-expand-transition>
                </v-img>
              </v-hover>
            </v-col>
            <v-row justify="center">
              <span>{{img.alt}}</span>
            </v-row>
          </v-card>
        </v-col>
      </v-row>
    </v-container>

    <lightbox id="mylightbox" ref="lightbox" :images="images" :timeoutDuration="5000" />
  </v-card>
</template>             
<script>
export default {
  props: {
    images: Array
  },

  methods: {
    showLightbox: function(imageName) {
      this.$refs.lightbox.show(imageName);
    }
  }
};
</script>
<style>
.v-card--reveal {
  align-items: center;
  bottom: 0;
  justify-content: center;
  opacity: 0.5;
  position: absolute;
  width: 100%;
}
</style>