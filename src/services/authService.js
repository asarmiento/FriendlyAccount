/**
 * Servicio de Autenticación
 * Maneja todas las operaciones relacionadas con autenticación
 */

import httpService from './httpService.js'
import { API_ENDPOINTS } from '@/config/api.js'

class AuthService {
  /**
   * Iniciar sesión
   * @param {object} credentials - Credenciales de login (email, password, session)
   */
  async login(credentials) {
    try {
      const response = await httpService.post(API_ENDPOINTS.AUTH.LOGIN, credentials)
      
      console.log('🔐 [AUTH_SERVICE] Respuesta del login:', response)
      
      if (response.success) {
        // La respuesta puede venir directamente o dentro de response.data
        const responseData = response.data || response
        const token = responseData.token
        const user = responseData.user
        
        console.log('📦 [AUTH_SERVICE] Procesando datos:', {
          hasToken: !!token,
          hasUser: !!user,
          tokenType: responseData.token_type,
          expiresAt: responseData.expires_at
        })
        
        if (token) {
          // Guardar token en localStorage usando httpService
          httpService.setAuthToken(token)
          console.log('✅ [AUTH_SERVICE] Token guardado:', token.substring(0, 20) + '...')
        }
        
        // Guardar información del usuario
        if (user) {
          localStorage.setItem('user', JSON.stringify(user))
          console.log('✅ [AUTH_SERVICE] Usuario guardado:', user.email)
        }
        
        // Guardar información adicional del token si existe
        if (responseData.expires_at) {
          localStorage.setItem('token_expires_at', responseData.expires_at)
        }
        
        if (responseData.token_type) {
          localStorage.setItem('token_type', responseData.token_type)
        }
      }
      
      return response
    } catch (error) {
      console.error('❌ [AUTH_SERVICE] Error en login:', error)
      return {
        success: false,
        error: {
          message: 'Error al iniciar sesión',
          errors: {}
        }
      }
    }
  }

  /**
   * Cerrar sesión
   */
  async logout() {
    try {
      // Intentar cerrar sesión en el servidor
      await httpService.post(API_ENDPOINTS.AUTH.LOGOUT)
    } catch (error) {
      console.error('Error al cerrar sesión en el servidor:', error)
    } finally {
      // Limpiar datos locales siempre
      this.clearLocalAuth()
    }
  }

  /**
   * Registrar nuevo usuario
   * @param {object} userData - Datos del usuario a registrar
   */
  async register(userData) {
    return await httpService.post(API_ENDPOINTS.AUTH.REGISTER, userData)
  }

  /**
   * Obtener información del usuario actual
   */
  async getCurrentUser() {
    return await httpService.get(API_ENDPOINTS.AUTH.ME)
  }

  /**
   * Refrescar token de autenticación
   */
  async refreshToken() {
    try {
      const refreshToken = localStorage.getItem('refresh_token')
      if (!refreshToken) {
        throw new Error('No refresh token available')
      }

      const response = await httpService.post(API_ENDPOINTS.AUTH.REFRESH, {
        refresh_token: refreshToken
      })

      if (response.success && response.data.token) {
        httpService.setAuthToken(response.data.token)
        
        if (response.data.refresh_token) {
          localStorage.setItem('refresh_token', response.data.refresh_token)
        }
      }

      return response
    } catch (error) {
      console.error('Error al refrescar token:', error)
      this.clearLocalAuth()
      return {
        success: false,
        error: {
          message: 'Error al refrescar sesión',
          errors: {}
        }
      }
    }
  }

  /**
   * Solicitar restablecimiento de contraseña
   * @param {string} email - Email del usuario
   */
  async forgotPassword(email) {
    return await httpService.post(API_ENDPOINTS.AUTH.FORGOT_PASSWORD, { email })
  }

  /**
   * Restablecer contraseña
   * @param {object} resetData - Datos para restablecer contraseña
   */
  async resetPassword(resetData) {
    return await httpService.post(API_ENDPOINTS.AUTH.RESET_PASSWORD, resetData)
  }

  /**
   * Verificar si el usuario está autenticado
   */
  isAuthenticated() {
    const token = localStorage.getItem(API_CONFIG.AUTH.TOKEN_KEY)
    const user  = localStorage.getItem('user')
    return !!(token && user)
  }

  /**
   * Obtener token de autenticación
   */
  getToken() {
    return localStorage.getItem(API_CONFIG.AUTH.TOKEN_KEY)
  }

  /**
   * Obtener información del usuario desde localStorage
   */
  getUser() {
    try {
      const userStr = localStorage.getItem('user')
      return userStr ? JSON.parse(userStr) : null
    } catch (error) {
      console.error('Error al parsear usuario:', error)
      return null
    }
  }

  /**
   * Limpiar datos de autenticación locales
   */
  clearLocalAuth() {
    httpService.clearAuth()              // esto ya quita API_CONFIG.AUTH.TOKEN_KEY
      localStorage.removeItem('user')
     localStorage.removeItem('token_type')
     localStorage.removeItem('token_expires_at')
     // si usas refresh_token:
    localStorage.removeItem(API_CONFIG.AUTH.REFRESH_TOKEN_KEY)
  }

  /**
   * Verificar si el token ha expirado
   */
  isTokenExpired() {
    const token = this.getToken()
    if (!token) return true

    try {
      // Decodificar el JWT para verificar expiración
      const payload = JSON.parse(atob(token.split('.')[1]))
      const currentTime = Date.now() / 1000
      return payload.exp < currentTime
    } catch (error) {
      console.error('Error al verificar expiración del token:', error)
      return true
    }
  }

  /**
   * Verificar y refrescar token si es necesario
   */
  async checkAndRefreshToken() {
    if (this.isTokenExpired()) {
      const refreshResult = await this.refreshToken()
      return refreshResult.success
    }
    return true
  }
}

// Exportar instancia única del servicio
export default new AuthService() 