/**
 * Servicio HTTP
 * Maneja todas las peticiones HTTP a la API externa usando axios
 */

import axios from 'axios'
import { API_CONFIG, getAuthHeaders, buildApiUrl } from '@/config/api.js'

// Crear instancia de axios con configuración base
const httpClient = axios.create({
  baseURL: API_CONFIG.BASE_URL,
  timeout: API_CONFIG.TIMEOUT,
  headers: API_CONFIG.DEFAULT_HEADERS
})

// Interceptor para peticiones (agregar token automáticamente)
httpClient.interceptors.request.use(
  (config) => {
    // Agregar token de autenticación si existe
    const token = localStorage.getItem(API_CONFIG.AUTH.TOKEN_KEY)
    if (token) {
      config.headers.Authorization = `${API_CONFIG.AUTH.TOKEN_PREFIX} ${token}`
    }
    
    // Log en desarrollo
    if (import.meta.env.DEV) {
      console.log('🚀 API Request:', {
        method: config.method?.toUpperCase(),
        url: config.url,
        data: config.data
      })
    }
    
    return config
  },
  (error) => {
    console.error('❌ Request Error:', error)
    return Promise.reject(error)
  }
)

// Interceptor para respuestas (manejar errores globalmente)
httpClient.interceptors.response.use(
  (response) => {
    // Log en desarrollo
    if (import.meta.env.DEV) {
      console.log('✅ API Response:', {
        status: response.status,
        url: response.config.url,
        data: response.data
      })
    }
    
    return response
  },
  (error) => {
    console.error('❌ Response Error:', error)
    
    // Manejar errores de autenticación
    if (error.response?.status === 401) {
      // Token expirado o inválido
      localStorage.removeItem(API_CONFIG.AUTH.TOKEN_KEY)
      localStorage.removeItem(API_CONFIG.AUTH.REFRESH_TOKEN_KEY)
      
      // Solo redirigir si no estamos ya en login y no es una petición de verificación
   /**   if (window.location.pathname !== '/login' && !error.config?.url?.includes('/auth/me')) {
        console.warn('🚫 [HTTP] Redirigiendo al login por error 401')
        window.location.href = '/login'
      }*/
    }
    
    // Manejar errores de servidor
    if (error.response?.status >= 500) {
      console.error('🔥 Server Error:', error.response.data)
    }
    
    return Promise.reject(error)
  }
)

// Clase del servicio HTTP
class HttpService {
  /**
   * Petición GET
   * @param {string} endpoint - Endpoint de la API
   * @param {object} params - Parámetros de consulta
   * @param {object} config - Configuración adicional de axios
   */
  async get(endpoint, params = {}, config = {}) {
    try {
      const response = await httpClient.get(endpoint, {
        params,
        ...config
      })
      return {
        success: true,
        data: response.data,
        status: response.status
      }
    } catch (error) {
      return this.handleError(error)
    }
  }

  /**
   * Petición POST
   * @param {string} endpoint - Endpoint de la API
   * @param {object} data - Datos a enviar
   * @param {object} config - Configuración adicional de axios
   */
  async post(endpoint, data = {}, config = {}) {
    try {
      const response = await httpClient.post(endpoint, data, config)
      return {
        success: true,
        data: response.data,
        status: response.status
      }
    } catch (error) {
      return this.handleError(error)
    }
  }

  /**
   * Petición PUT
   * @param {string} endpoint - Endpoint de la API
   * @param {object} data - Datos a enviar
   * @param {object} config - Configuración adicional de axios
   */
  async put(endpoint, data = {}, config = {}) {
    try {
      const response = await httpClient.put(endpoint, data, config)
      return {
        success: true,
        data: response.data,
        status: response.status
      }
    } catch (error) {
      return this.handleError(error)
    }
  }

  /**
   * Petición PATCH
   * @param {string} endpoint - Endpoint de la API
   * @param {object} data - Datos a enviar
   * @param {object} config - Configuración adicional de axios
   */
  async patch(endpoint, data = {}, config = {}) {
    try {
      const response = await httpClient.patch(endpoint, data, config)
      return {
        success: true,
        data: response.data,
        status: response.status
      }
    } catch (error) {
      return this.handleError(error)
    }
  }

  /**
   * Petición DELETE
   * @param {string} endpoint - Endpoint de la API
   * @param {object} config - Configuración adicional de axios
   */
  async delete(endpoint, config = {}) {
    try {
      const response = await httpClient.delete(endpoint, config)
      return {
        success: true,
        data: response.data,
        status: response.status
      }
    } catch (error) {
      return this.handleError(error)
    }
  }

  /**
   * Subir archivos
   * @param {string} endpoint - Endpoint de la API
   * @param {FormData} formData - Datos del formulario con archivos
   * @param {function} onUploadProgress - Callback para progreso de subida
   */
  async upload(endpoint, formData, onUploadProgress = null) {
    try {
      const config = {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }
      
      if (onUploadProgress) {
        config.onUploadProgress = onUploadProgress
      }
      
      const response = await httpClient.post(endpoint, formData, config)
      return {
        success: true,
        data: response.data,
        status: response.status
      }
    } catch (error) {
      return this.handleError(error)
    }
  }

  /**
   * Manejar errores de manera consistente
   * @param {object} error - Error de axios
   */
  handleError(error) {
    const errorResponse = {
      success: false,
      error: {
        message: 'Error inesperado',
        status: 500,
        errors: {}
      }
    }

    if (error.response) {
      // Error de respuesta del servidor
      errorResponse.error = {
        message: error.response.data?.message || 'Error del servidor',
        status: error.response.status,
        errors: error.response.data?.errors || {}
      }
    } else if (error.request) {
      // Error de red
      errorResponse.error = {
        message: 'Error de conexión. Verifica tu conexión a internet.',
        status: 0,
        errors: {}
      }
    } else {
      // Error de configuración
      errorResponse.error = {
        message: error.message || 'Error inesperado',
        status: 500,
        errors: {}
      }
    }

    return errorResponse
  }

  /**
   * Configurar token de autenticación
   * @param {string} token - Token de autenticación
   */
  setAuthToken(token) {
    if (token) {
      localStorage.setItem(API_CONFIG.AUTH.TOKEN_KEY, token)
      httpClient.defaults.headers.common['Authorization'] = `${API_CONFIG.AUTH.TOKEN_PREFIX} ${token}`
    } else {
      localStorage.removeItem(API_CONFIG.AUTH.TOKEN_KEY)
      delete httpClient.defaults.headers.common['Authorization']
    }
  }

  /**
   * Limpiar autenticación
   */
  clearAuth() {
    localStorage.removeItem(API_CONFIG.AUTH.TOKEN_KEY)
    localStorage.removeItem(API_CONFIG.AUTH.REFRESH_TOKEN_KEY)
    delete httpClient.defaults.headers.common['Authorization']
  }

  /**
   * Obtener instancia de axios para casos especiales
   */
  getAxiosInstance() {
    return httpClient
  }
}

// Exportar instancia única del servicio
export default new HttpService() 