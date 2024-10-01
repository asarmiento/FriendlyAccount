export default {
  async login({dispatch}, credentials) {
    await axios.post("/login", credentials);
    return dispatch("getUser");


  },
  getUser({commit}) {
    axios.get("/session/usuario").then((response) => {
      console.log("si entro");
      console.log(response.data.user);
      commit("SET_USER", response.data.user);
      console.log(response.data.selectProducts);
      commit("SET_SELECTPRODUCTS", response.data.selectProducts);
      console.log(response.data.selectCustomers);
      commit("SET_SELECTCUSTOMERS", response.data.selectCustomers);
      console.log(response.data.activities);
      commit("SET_ACTIVITIES", response.data.activities);
      console.log(response.data.sysconf);
      commit("SET_SYSCONF", response.data.sysconf);

    }).catch(() => {
      commit("SET_USER", null);
      commit("SET_SELECTPRODUCTS", null);
      commit("SET_SELECTCUSTOMERSTICKET", null);
      commit("SET_ACTIVITIES", null);
      commit("SET_SYSCONF", null);
    });
  },
  dataSysconfAction(context) {
    axios.get("/sysconfig_bussines").then((response) => {
      localStorage.setItem('sysconfData', JSON.stringify(response.data));
      context.commit('sysconfMuttation', {
        sysconf: response.data
      })
    });
  },
  tryDataSysconfAction(context) {

      const sysconf = localStorage.getItem('sysconfData');
      context.commit('sysconfMuttation', {
        sysconf: JSON.parse(sysconf)
      })

  }
}
