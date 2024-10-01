import mutations from './mutations.js'
import actions from './actions.js'
import getters from './getters.js'

export default {
  state() {
    return {
      user:null,
      auth:false,
      activities:null,
      sysconf:null,
      selectProducts:null,
      selectCustomers:null
    };
  },
  mutations,
  actions,
  getters
}
