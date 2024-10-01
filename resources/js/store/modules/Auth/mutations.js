export default {
  SET_USER(state,user){
    state.user = user;
    state.auth = Boolean(user);
  },
  SET_ACTIVITIES(state,activities){
    state.activities = activities;
  },
  SET_SYSCONF(state,sysconf){
    state.sysconf = sysconf;
  },
  SET_SELECTPRODUCTS(state,products){
    state.selectProducts = products
  },
  SET_SELECTCUSTOMERS(state,customers){
    state.selectCustomers = customers
  },
  sysconfMuttation(state, payload){
    state.sysconf= payload.sysconf
  }
}
