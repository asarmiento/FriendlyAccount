import store from '../store'
import { getAuthToken } from '../plugins/axios'

/**
 * Middleware para rutas que requieren autenticación
 */
export const requireAuth = async (to, from, next) => {
    console.log('🔐 [MIDDLEWARE] requireAuth - Verificando autenticación para:', to.path)
    
    // Verificar si hay token
    const token = getAuthToken()
    const isAuthenticated = store.getters['auth/isAuthenticated']
    
    if (!token) {
        console.log('❌ [MIDDLEWARE] No hay token, redirigiendo a login')
        return next('/login')
    }
    
    if (!isAuthenticated) {
        console.log('🔄 [MIDDLEWARE] Token existe pero no autenticado, verificando...')
        
        try {
            const result = await store.dispatch('auth/checkAuth')
            if (result.success) {
                console.log('✅ [MIDDLEWARE] Autenticación verificada, continuando')
                return next()
            } else {
                console.log('❌ [MIDDLEWARE] Token inválido, redirigiendo a login')
                return next('/login')
            }
        } catch (error) {
            console.error('❌ [MIDDLEWARE] Error verificando auth:', error)
            return next('/login')
        }
    }
    
    console.log('✅ [MIDDLEWARE] Usuario autenticado, continuando')
    next()
}

/**
 * Middleware para rutas que requieren estar NO autenticado (como login)
 */
export const requireGuest = async (to, from, next) => {
    console.log('👤 [MIDDLEWARE] requireGuest - Verificando guest para:', to.path)
    
    const isAuthenticated = store.getters['auth/isAuthenticated']
    const token = getAuthToken()
    
    // Si hay token, verificar si es válido antes de redirigir
    if (token && isAuthenticated) {
        console.log('🔄 [MIDDLEWARE] Usuario ya autenticado, verificando validez del token...')
        
        try {
            const result = await store.dispatch('auth/checkAuth')
            if (result.success) {
                console.log('✅ [MIDDLEWARE] Token válido, redirigiendo a schools')
                return next('/schools')
            } else {
                console.log('❌ [MIDDLEWARE] Token inválido, permitiendo acceso a login')
                // Token inválido, limpiar y permitir acceso a login
                store.commit('auth/LOGOUT')
                return next()
            }
        } catch (error) {
            console.error('❌ [MIDDLEWARE] Error verificando token:', error)
            // En caso de error, limpiar y permitir acceso a login
            store.commit('auth/LOGOUT')
            return next()
        }
    }
    
    console.log('✅ [MIDDLEWARE] Usuario no autenticado, continuando')
    next()
}

/**
 * Middleware para rutas que requieren permisos específicos
 */
export const requirePermissions = (permissions) => {
    return async (to, from, next) => {
        console.log('🛡️ [MIDDLEWARE] requirePermissions - Verificando permisos:', permissions)
        
        // Primero verificar autenticación
        await requireAuth(to, from, (result) => {
            if (result && result !== true) {
                // Si requireAuth redirige, seguir la redirección
                return next(result)
            }
            
            // Verificar permisos específicos
            const user = store.getters['auth/user']
            if (!user) {
                console.log('❌ [MIDDLEWARE] No hay usuario, redirigiendo a login')
                return next('/login')
            }
            
            // Aquí puedes agregar lógica específica de permisos
            // Por ejemplo, verificar user.type_user_id o user.permissions
            
            console.log('✅ [MIDDLEWARE] Permisos verificados, continuando')
            next()
        })
    }
}

/**
 * Middleware para cargar datos necesarios en ciertas rutas
 */
export const loadSchools = async (to, from, next) => {
    console.log('🏢 [MIDDLEWARE] loadSchools - Cargando schools...')
    
    try {
        const schools = store.getters['auth/schools']
        if (schools.length === 0) {
            console.log('🔄 [MIDDLEWARE] No hay schools en store, cargando...')
            await store.dispatch('auth/fetchUserSchools')
        }
        
        console.log('✅ [MIDDLEWARE] Schools disponibles, continuando')
        next()
    } catch (error) {
        console.error('❌ [MIDDLEWARE] Error cargando schools:', error)
        next() // Continuar aunque falle la carga
    }
} 