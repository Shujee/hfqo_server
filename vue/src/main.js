import Vue from 'vue';
import App from './App.vue';
import vuetify from './plugins/vuetify';
import router from './router';
import store from './store';
import moment from 'moment-timezone';
import '@mdi/font/css/materialdesignicons.css';

import Lightbox from 'vue-my-photos'
Vue.component('lightbox', Lightbox);

Vue.use(Lightbox)

moment.tz.setDefault('UTC');

Vue.config.productionTip = false

new Vue({
  vuetify,
  router,
  store,
  Lightbox,
  render: h => h(App)
}).$mount('#app')