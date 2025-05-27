/**
 * Configuración de la API
 * Este archivo centraliza toda la configuración relacionada con la API externa
 */

// Configuración base de la API
export const API_CONFIG = {
  // URL base de la API (desde variables de entorno)
  BASE_URL: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api',
  
  // Timeout para las peticiones (en milisegundos)
  TIMEOUT: parseInt(import.meta.env.VITE_API_TIMEOUT) || 10000,
  
  // Headers por defecto
  DEFAULT_HEADERS: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  },
  
  // Configuración de autenticación
  AUTH: {
    TOKEN_KEY: 'auth_token',
    TOKEN_PREFIX: 'Bearer',
    REFRESH_TOKEN_KEY: 'refresh_token'
  }
}

// Endpoints de la API
export const API_ENDPOINTS = {
  // Autenticación
  AUTH: {
    LOGIN: '/auth/login',
    LOGOUT: '/auth/logout',
    REFRESH: '/auth/refresh',
    ME: '/auth/me',
    REGISTER: '/auth/register',
    FORGOT_PASSWORD: '/auth/forgot-password',
    RESET_PASSWORD: '/auth/reset-password'
  },
  
  // Escuelas/Instituciones
  SCHOOLS: {
    LIST: '/schools',
    SHOW: '/schools/:id',
    CREATE: '/schools',
    UPDATE: '/schools/:id',
    DELETE: '/schools/:id'
  },
  
  // Usuarios
  USERS: {
    LIST: '/users',
    SHOW: '/users/:id',
    CREATE: '/users',
    UPDATE: '/users/:id',
    DELETE: '/users/:id'
  },
  
  // Estudiantes
  STUDENTS: {
    LIST: '/students',
    SHOW: '/students/:id',
    CREATE: '/students',
    UPDATE: '/students/:id',
    DELETE: '/students/:id'
  },
  
  // Reportes
  REPORTS: {
    DASHBOARD: '/reports/dashboard',
    ACADEMIC: '/reports/academic',
    FINANCIAL: '/reports/financial'
  }
}

// Función helper para construir URLs completas
export const buildApiUrl = (endpoint, params = {}) => {
  let url = API_CONFIG.BASE_URL + endpoint
  
  // Reemplazar parámetros en la URL (ej: :id)
  Object.keys(params).forEach(key => {
    url = url.replace(`:${key}`, params[key])
  })
  
  return url
}

// Función helper para obtener headers con autenticación
export const getAuthHeaders = () => {
  const token = localStorage.getItem(API_CONFIG.AUTH.TOKEN_KEY)
  const headers = { ...API_CONFIG.DEFAULT_HEADERS }
  
  if (token) {
    headers.Authorization = `${API_CONFIG.AUTH.TOKEN_PREFIX} ${token}`
  }
  
  return headers
}

// Configuración del entorno
export const ENV_CONFIG = {
  APP_NAME: import.meta.env.VITE_APP_NAME || 'Friendly Account',
  APP_VERSION: import.meta.env.VITE_APP_VERSION || '1.0.0',
  IS_DEV: import.meta.env.DEV,
  IS_PROD: import.meta.env.PROD
}

// Exportar configuración por defecto
export default {
  API_CONFIG,
  API_ENDPOINTS,
  ENV_CONFIG,
  buildApiUrl,
  getAuthHeaders
} 