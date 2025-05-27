import axios from 'axios'

export default {
  SET_USER(state, user) {
    state.user = user;
    state.isAuthenticated = Boolean(user);
  },
  
  SET_TOKEN(state, token) {
    state.token = token;
    if (token) {
      // Guardar en localStorage
      localStorage.setItem('auth_token', token);
      // Configurar axios headers
      axios.defaults.headers.common.Authorization = `Bearer ${token}`;
      console.log('✅ [AUTH] Token configurado en axios:', token.substring(0, 20) + '...');
    } else {
      // Limpiar localStorage
      localStorage.removeItem('auth_token');
      // Limpiar axios headers
      delete axios.defaults.headers.common.Authorization;
      console.log('🗑️ [AUTH] Token removido de axios y localStorage');
    }
  },
  
  SET_LOADING(state, isLoading) {
    state.isLoading = isLoading;
  },
  
  SET_ERROR(state, error) {
    state.error = error;
  },
  
  CLEAR_ERROR(state) {
    state.error = null;
  },
  
  SET_SCHOOLS(state, schools) {
    state.schools = schools;
  },
  
  SET_ACTIVITIES(state, activities) {
    state.activities = activities;
  },
  
  SET_SYSCONF(state, sysconf) {
    state.sysconf = sysconf;
  },
  
  SET_SELECTPRODUCTS(state, products) {
    state.selectProducts = products;
  },
  
  SET_SELECTCUSTOMERS(state, customers) {
    state.selectCustomers = customers;
  },
  
  LOGOUT(state) {
    state.user = null;
    state.token = null;
    state.isAuthenticated = false;
    state.schools = [];
    state.activities = null;
    state.sysconf = null;
    state.selectProducts = null;
    state.selectCustomers = null;
    state.error = null;
    
    // Limpiar localStorage
    localStorage.removeItem('auth_token');
    // Limpiar axios headers
    delete axios.defaults.headers.common.Authorization;
    console.log('🚪 [AUTH] Logout completo - estado y token limpiados');
  },
  
  sysconfMuttation(state, payload) {
    state.sysconf = payload.sysconf;
  }
}
