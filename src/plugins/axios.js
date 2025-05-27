import axios from 'axios'
import {API_CONFIG} from "@/config/api.js";

// Configuración base de axios
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.headers.common['Accept'] = 'application/json'
axios.defaults.headers.common['Content-Type'] = 'application/json'

// Configurar el token CSRF
const token = document.head.querySelector('meta[name="csrf-token"]')
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token')
}

// Función para obtener el token de Sanctum del localStorage
export const getAuthToken = () => {
    const token = localStorage.getItem(API_CONFIG.AUTH.TOKEN_KEY)
    console.log('🔍 [AXIOS] getAuthToken llamado, token encontrado:', token ? token.substring(0, 20) + '...' : 'null')
    return token
}

// Función para establecer el token de Sanctum (ahora manejado por el store)
export const setAuthToken = (token) => {
    console.log('🔧 [AXIOS] setAuthToken llamado con:', token ? token.substring(0, 20) + '...' : 'null')
    
    if (token) {
        localStorage.setItem(API_CONFIG.AUTH.TOKEN_KEY, token)
        axios.defaults.headers.common.Authorization = `Bearer ${token}`
        console.log('✅ [AXIOS] Token configurado en axios')
    } else {
        localStorage.removeItem(API_CONFIG.AUTH.TOKEN_KEY)
        delete axios.defaults.headers.common.Authorization
        console.log('🗑️ [AXIOS] Token removido de axios y localStorage')
    }
}

// Establecer el token si ya existe en localStorage al inicializar
const savedToken = getAuthToken()
if (savedToken) {
    console.log('🔄 [AXIOS] Token encontrado en localStorage al inicializar, configurando...')
    setAuthToken(savedToken)
} else {
    console.log('❌ [AXIOS] No hay token en localStorage al inicializar')
}

// Configurar la base URL si es necesario
// axios.defaults.baseURL = process.env.MIX_APP_URL || 'http://localhost'

// Interceptors para manejo de errores globales
axios.interceptors.request.use(
    config => {
        // Log detallado de requests
        console.log('🌐 [AXIOS] Request iniciado:', {
            method: config.method?.toUpperCase(),
            url: config.url,
            hasAuthHeader: !!config.headers.Authorization,
            authHeaderValue: config.headers.Authorization ? config.headers.Authorization.substring(0, 30) + '...' : 'NO PRESENTE'
        })
        
        // Asegurar que el token esté presente en peticiones API autenticadas
        if (config.url && config.url.includes('/api/') && !config.url.includes('/api/login')) {
            const storedToken = localStorage.getItem(API_CONFIG.AUTH.TOKEN_KEY)
            
            if (storedToken && !config.headers.Authorization) {
                config.headers.Authorization = `Bearer ${storedToken}`
                console.log('🔧 [AXIOS] Token agregado automáticamente a la petición')
            }
        }
        
        return config
    },
    error => {
        console.error('❌ [AXIOS] Request Error:', error)
        return Promise.reject(error)
    }
)

axios.interceptors.response.use(
    response => {
        // Log detallado de responses exitosas
        console.log('✅ [AXIOS] Response exitosa:', {
            url: response.config.url,
            method: response.config.method?.toUpperCase(),
            status: response.status
        })
        return response
    },
    error => {
        // Manejo global de errores con logs detallados
        console.error('❌ [AXIOS] Response Error:', {
            url: error.config?.url,
            method: error.config?.method?.toUpperCase(),
            message: error.message,
            status: error.response?.status,
            data: error.response?.data
        })
        
        if (error.response) {
            const { status, data } = error.response
            
            switch (status) {
                case 401:
                    // No autorizado - limpiar token y redirigir
                    console.warn('🚫 [AXIOS] Usuario no autorizado (401)')
                    localStorage.removeItem('auth_token')
                    delete axios.defaults.headers.common.Authorization
                    
                    // Solo redirigir si no estamos ya en login y no es una petición de verificación
                   /* if (window.location.pathname !== '/login' && !error.config?.url?.includes('/auth/me')) {
                        console.warn('🚫 [AXIOS] Redirigiendo al login...')
                        window.location.href = '/login'
                    }*/
                    break
                    
                case 403:
                    console.error('🚫 [AXIOS] Acceso prohibido')
                    break
                    
                case 404:
                    console.error('🔍 [AXIOS] Recurso no encontrado')
                    break
                    
                case 422:
                    console.error('📝 [AXIOS] Errores de validación:', data.errors)
                    break
                    
                case 500:
                    console.error('🔥 [AXIOS] Error interno del servidor')
                    break
                    
                default:
                    console.error('⚠️ [AXIOS] Error HTTP:', status, data)
            }
        } else if (error.request) {
            console.error('🌐 [AXIOS] Error de red o servidor no disponible')
        } else {
            console.error('⚙️ [AXIOS] Error en la configuración de la petición:', error.message)
        }
        
        return Promise.reject(error)
    }
)

// Hacer axios disponible globalmente
window.axios = axios

// Hacer las funciones de token disponibles globalmente
window.setAuthToken = setAuthToken
window.getAuthToken = getAuthToken

export default axios 