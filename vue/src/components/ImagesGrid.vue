<template>
  <v-card flat class="ma-4">
      <v-card-text class="overflow-hidden py-0">
        <v-layout class="justify-start align-start text-left" row v-vuse-scrollbar:x>
          <v-flex class="justify-start"
            v-for="media in images"
            :key="media.filename"
            px-5
            pb-2
          >
            <v-row class="justify-center">
              <img :src="media.filename" height="70px" width="auto">
            </v-row>
            <v-row class="justify-center">
                 {{media.timestamp}}
            </v-row>
          </v-flex>
        </v-layout>
      </v-card-text>
    </v-card>
</template>
<script>
export default {
  props: {
    images: Array
  }
};
</script>