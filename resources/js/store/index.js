import Vue from 'vue'
import Vuex from 'vuex'
import auth from './modules/Auth'
import products from './modules/Products'
import schedule from './modules/Schedules'
Vue.use(Vuex);
 const store = new Vuex.Store({
      namespaced: true,
      modules: {
        auth,
        products,
        schedule
      }
});

export default store;
