import authService from '@/services/authService.js'
import httpService from '@/services/httpService.js'
import axios from 'axios'

export default {
  // Acción principal de login
  async login({ commit }, credentials) {
    commit('SET_LOADING', true);
    commit('CLEAR_ERROR');
    
    try {
      console.log('🔐 [AUTH] Iniciando login con:', { email: credentials.email, session: credentials.session });
      
      const response = await authService.login(credentials);
      
      console.log('📥 [AUTH] Respuesta del servidor:', response);
      
      if (response.success) {
        // La respuesta puede venir directamente o dentro de response.data
        const responseData = response.data || response;
        const { user, token } = responseData;
        
        console.log('📦 [AUTH] Datos recibidos del login:', {
          user: user,
          token: token ? token.substring(0, 20) + '...' : 'NO TOKEN',
          tokenType: responseData.token_type,
          expiresAt: responseData.expires_at
        });
        
        // Configurar usuario y token en el store
        commit('SET_USER', user);
        commit('SET_TOKEN', token);
        
        console.log('✅ [AUTH] Login exitoso para usuario:', user.email);
        
        return { 
          success: true, 
          user, 
          token,
          message: responseData.message || 'Inicio de sesión exitoso'
        };
      } else {
        const errorMessage = response.error.message || 'Error en el login';
        commit('SET_ERROR', errorMessage);
        
        console.log('❌ [AUTH] Login fallido:', errorMessage);
        
        return response;
      }
    } catch (error) {
      console.error('❌ [AUTH] Error durante el login:', error);
      
      const errorMessage = 'Error de conexión';
      commit('SET_ERROR', errorMessage);
      
      return { 
        success: false, 
        error: { 
          message: errorMessage,
          errors: {}
        }
      };
    } finally {
      commit('SET_LOADING', false);
    }
  },

  // Acción para obtener las escuelas del usuario
  async fetchUserSchools({ commit }) {
    console.log('🏢 [AUTH] Iniciando fetchUserSchools...');
    commit('SET_LOADING', true);
    commit('CLEAR_ERROR');
    
    try {
      console.log('🔄 [AUTH] Haciendo petición a /user-schools...');
      const response = await httpService.get('/schools/user-schools');
      
      console.log('✅ [AUTH] Respuesta recibida:', response);
      
      if (response.success) {
        const schools = response.data.schools || [];
        const user = response.data.user || null;
        const canCreateAccounts = response.data.can_create_accounts || false;
        
        console.log('📊 [AUTH] Datos procesados:', {
          schoolsCount: schools.length,
          schools: schools,
          user: user,
          canCreateAccounts: canCreateAccounts
        });
        
        commit('SET_SCHOOLS', schools);
        
        if (user) {
          commit('SET_USER', user);
        }
        
        console.log('✅ [AUTH] Schools guardadas en el store');
        
        return { 
          success: true, 
          schools, 
          data: response.data 
        };
      } else {
        const errorMessage = response.error.message || 'Error al cargar las instituciones';
        commit('SET_ERROR', errorMessage);
        return { success: false, error: errorMessage };
      }
    } catch (error) {
      console.error('❌ [AUTH] Error en fetchUserSchools:', error);
      
      const errorMessage = 'Error al cargar las instituciones';
      commit('SET_ERROR', errorMessage);
      return { success: false, error: errorMessage };
    } finally {
      commit('SET_LOADING', false);
      console.log('🏁 [AUTH] fetchUserSchools finalizado');
    }
  },

  // Acción para acceder a una escuela específica
  async accessSchool({ commit }, token) {
    commit('SET_LOADING', true);
    commit('CLEAR_ERROR');
    
    try {
      const response = await httpService.post('/schools/access', { token });
      
      if (response.success) {
        return { 
          success: true, 
          school: response.data.school,
          redirectUrl: response.data.redirect_url 
        };
      } else {
        const errorMessage = response.error.message || 'No tiene permisos para acceder a esta institución';
        commit('SET_ERROR', errorMessage);
        return { success: false, error: errorMessage };
      }
    } catch (error) {
      const errorMessage = 'Error al acceder a la institución';
      commit('SET_ERROR', errorMessage);
      return { success: false, error: errorMessage };
    } finally {
      commit('SET_LOADING', false);
    }
  },

  // Acción de logout
  async logout({ commit }) {
    commit('SET_LOADING', true);
    
    try {
      console.log('🚪 [AUTH] Iniciando logout...');
      
      // Usar el servicio de autenticación para logout
      await authService.logout();
      console.log('✅ [AUTH] Logout exitoso');
    } catch (error) {
      // Aunque falle la petición al servidor, limpiar el estado local
      console.error('❌ [AUTH] Error en logout:', error);
      console.log('🧹 [AUTH] Limpiando estado local de todas formas...');
    } finally {
      // Siempre limpiar el estado local
      commit('LOGOUT');
      commit('SET_LOADING', false);
      console.log('✅ [AUTH] Logout completado');
    }
    
    return { success: true };
  },

  // Acción para verificar autenticación
  async checkAuth({ commit }) {
    console.log('🔐 [AUTH] Iniciando checkAuth...');
    
    try {
      console.log('🔄 [AUTH] Haciendo petición a /auth/me...');
      const response = await httpService.get('/auth/me');
      
      console.log('✅ [AUTH] Respuesta de auth/me:', response);
      
      if (response.success && response.data.user) {
        console.log('✅ [AUTH] Usuario autenticado, guardando en store...');
        commit('SET_USER', response.data.user);
        return { success: true, user: response.data.user };
      } else {
        console.log('❌ [AUTH] Usuario no autenticado, limpiando store...');
        commit('LOGOUT');
        return { success: false };
      }
    } catch (error) {
      console.error('❌ [AUTH] Error en checkAuth:', error);
      commit('LOGOUT');
      return { success: false };
    }
  },

  // Acción para obtener datos del usuario (legacy)
  async getUser({ commit }) {
    try {
      const response = await axios.get("/session/usuario");
      
      console.log("✅ [AUTH] Datos de usuario obtenidos");
      console.log(response.data.user);
      
      commit("SET_USER", response.data.user);
      commit("SET_SELECTPRODUCTS", response.data.selectProducts);
      commit("SET_SELECTCUSTOMERS", response.data.selectCustomers);
      commit("SET_ACTIVITIES", response.data.activities);
      commit("SET_SYSCONF", response.data.sysconf);
      
      return { success: true, data: response.data };
    } catch (error) {
      console.error("❌ [AUTH] Error obteniendo datos de usuario:", error);
      
      commit("SET_USER", null);
      commit("SET_SELECTPRODUCTS", null);
      commit("SET_SELECTCUSTOMERS", null);
      commit("SET_ACTIVITIES", null);
      commit("SET_SYSCONF", null);
      
      return { success: false, error: error.message };
    }
  },

  // Acción para obtener configuración del sistema
  async dataSysconfAction({ commit }) {
    try {
      const response = await axios.get("/sysconfig_bussines");
      
      // Guardar en localStorage para persistencia
      localStorage.setItem('sysconfData', JSON.stringify(response.data));
      
      commit('sysconfMuttation', {
        sysconf: response.data
      });
      
      console.log('✅ [AUTH] Configuración del sistema obtenida y guardada');
      return { success: true, data: response.data };
    } catch (error) {
      console.error('❌ [AUTH] Error obteniendo configuración del sistema:', error);
      return { success: false, error: error.message };
    }
  },

  // Acción para intentar cargar configuración desde localStorage
  tryDataSysconfAction({ commit }) {
    try {
      const sysconf = localStorage.getItem('sysconfData');
      
      if (sysconf) {
        commit('sysconfMuttation', {
          sysconf: JSON.parse(sysconf)
        });
        console.log('✅ [AUTH] Configuración del sistema cargada desde localStorage');
        return { success: true };
      } else {
        console.log('ℹ️ [AUTH] No hay configuración del sistema en localStorage');
        return { success: false, error: 'No hay datos en localStorage' };
      }
    } catch (error) {
      console.error('❌ [AUTH] Error cargando configuración desde localStorage:', error);
      return { success: false, error: error.message };
    }
  },

  // Acción para inicializar el módulo Auth (configurar token si existe)
  initializeAuth({ commit, dispatch }) {
    console.log('🚀 [AUTH] Inicializando módulo Auth...');
    
    const token = localStorage.getItem('auth_token');
    
    if (token) {
      console.log('🔑 [AUTH] Token encontrado en localStorage, configurando...');
      commit('SET_TOKEN', token);
      
      // Verificar si el token es válido
      return dispatch('checkAuth');
    } else {
      console.log('❌ [AUTH] No hay token en localStorage');
      return Promise.resolve({ success: false });
    }
  }
}
