import { computed } from 'vue'
import { useStore } from 'vuex'

export function useAuth() {
  const store = useStore()
  
  // Computed properties que mapean al store
  const user = computed(() => store.getters['auth/user'])
  const token = computed(() => store.getters['auth/token'])
  const isAuthenticated = computed(() => store.getters['auth/isAuthenticated'])
  const isLoading = computed(() => store.getters['auth/isLoading'])
  const error = computed(() => store.getters['auth/error'])
  const schools = computed(() => store.getters['auth/schools'])
  
  // Métodos que llaman a las acciones del store
  const login = async (credentials) => {
    return await store.dispatch('auth/login', credentials)
  }
  
  const logout = async () => {
    return await store.dispatch('auth/logout')
  }
  
  const checkAuth = async () => {
    return await store.dispatch('auth/checkAuth')
  }
  
  const fetchUserSchools = async () => {
    return await store.dispatch('auth/fetchUserSchools')
  }
  
  const accessSchool = async (token) => {
    return await store.dispatch('auth/accessSchool', token)
  }
  
  const initializeAuth = async () => {
    return await store.dispatch('auth/initializeAuth')
  }
  
  return {
    // State
    user,
    token,
    isAuthenticated,
    isLoading,
    error,
    schools,
    
    // Actions
    login,
    logout,
    checkAuth,
    fetchUserSchools,
    accessSchool,
    initializeAuth
  }
} 