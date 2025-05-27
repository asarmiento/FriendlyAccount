import { createStore } from 'vuex'
import Auth from './modules/Auth'

export default createStore({
    modules: {
        auth: Auth
    },
    
    state: {
        // Estado global que no pertenece a ningún módulo específico
        appLoading: false,
        notifications: []
    },
    
    mutations: {
        SET_APP_LOADING(state, isLoading) {
            state.appLoading = isLoading
        },
        
        ADD_NOTIFICATION(state, notification) {
            state.notifications.push({
                id: Date.now(),
                type: notification.type || 'info',
                message: notification.message,
                timeout: notification.timeout || 5000,
                ...notification
            })
        },
        
        REMOVE_NOTIFICATION(state, notificationId) {
            state.notifications = state.notifications.filter(n => n.id !== notificationId)
        },
        
        CLEAR_NOTIFICATIONS(state) {
            state.notifications = []
        }
    },
    
    actions: {
        // Acción para mostrar notificaciones
        showNotification({ commit }, notification) {
            commit('ADD_NOTIFICATION', notification)
            
            if (notification.timeout && notification.timeout > 0) {
                setTimeout(() => {
                    commit('REMOVE_NOTIFICATION', notification.id || Date.now())
                }, notification.timeout)
            }
        },
        
        // Acción para inicializar la aplicación
        async initializeApp({ dispatch }) {
            console.log('🚀 [STORE] Inicializando aplicación...')
            
            try {
                // Inicializar el módulo de autenticación
                await dispatch('auth/initializeAuth')
                console.log('✅ [STORE] Aplicación inicializada correctamente')
            } catch (error) {
                console.error('❌ [STORE] Error inicializando aplicación:', error)
            }
        }
    },
    
    getters: {
        // Getters globales
        appLoading: (state) => state.appLoading,
        notifications: (state) => state.notifications,
        hasNotifications: (state) => state.notifications.length > 0,
        
        // Getters que mapean al módulo Auth para compatibilidad
        user: (state, getters) => getters['auth/user'],
        isAuthenticated: (state, getters) => getters['auth/isAuthenticated'],
        token: (state, getters) => getters['auth/token'],
        isLoading: (state, getters) => getters['auth/isLoading'],
        error: (state, getters) => getters['auth/error'],
        schools: (state, getters) => getters['auth/schools']
    }
})
