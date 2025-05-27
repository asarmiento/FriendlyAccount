import mutations from './mutations.js'
import actions from './actions.js'
import getters from './getters.js'

export default {
  namespaced: true,
  state() {
    const token = localStorage.getItem('auth_token')
    return {
      user: null,
      token: token,
      isAuthenticated: !!token, // Inicializar como true si hay token
      isLoading: false,
      error: null,
      schools: [],
      activities: null,
      sysconf: null,
      selectProducts: null,
      selectCustomers: null
    };
  },
  mutations,
  actions,
  getters
}
