import Vue from 'vue'
import App from './App.vue'
import VueRouter from 'vue-router'

import EstadoComponent from './components/Estado.vue';
import CidadeComponent from './components/Cidade.vue';
Vue.use(VueRouter)



const routes = [
  { path: '/estado', component: EstadoComponent },
  { path: '/cidade', component: CidadeComponent },
]

// 3. Create the router instance and pass the `routes` option
// You can pass in additional options here, but let's
// keep it simple for now.
const router = new VueRouter({
  routes // short for `routes: routes`
})
Vue.config.productionTip = false


new Vue({
  router,
  render: h => h(App),
}).$mount('#app')
