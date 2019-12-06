/* eslint-disable no-console */
import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import store from '../store/index'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
    meta: { requiresLogin: true }
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { requiresLogin: false }
  },
  {
    path: '/about',
    name: 'about',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/About.vue'),
    meta: { requiresLogin: false }
  }
]

const router = new VueRouter({
  routes
})

router.beforeEach( (to, from, next) => {
  console.log('HERE1');
  if (to.matched.some(record => record.meta.requiresLogin) && !store.getters.loggedIn) {
    console.log('HERE');
      next("/login");
  } else {
      next()
  }
});

export default router