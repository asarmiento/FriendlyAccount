export default {
  // Getters básicos de autenticación
  user: (state) => state.user,
  token: (state) => state.token,
  isAuthenticated: (state) => state.isAuthenticated,
  isLoading: (state) => state.isLoading,
  error: (state) => state.error,
  
  // Getters para datos específicos
  schools: (state) => state.schools,
  activities: (state) => state.activities,
  sysconf: (state) => state.sysconf,
  selectProducts: (state) => state.selectProducts,
  selectCustomers: (state) => state.selectCustomers,
  
  // Getters computados
  hasToken: (state) => !!state.token,
  hasUser: (state) => !!state.user,
  hasSchools: (state) => state.schools && state.schools.length > 0,
  
  // Getter para información del usuario
  userInfo: (state) => {
    if (!state.user) return null;
    
    return {
      id: state.user.id,
      name: state.user.name,
      email: state.user.email,
      // Agregar más campos según sea necesario
    };
  },
  
  // Getter para verificar si hay errores
  hasError: (state) => !!state.error,
  
  // Getter para el estado de carga
  isNotLoading: (state) => !state.isLoading
}
