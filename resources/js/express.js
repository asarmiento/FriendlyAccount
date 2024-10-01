import Vue from 'vue';
import axios from 'axios';
import VueAxios from 'vue-axios';

Vue.use(VueAxios, axios);

import App from './components/App.vue';

export const bus = new Vue();

new Vue({
    el: '#app',
    render: h => h(App)
});