import { computed } from 'vue'
import { useStore } from 'vuex'

export function useNotifications() {
  const store = useStore()
  
  // Computed properties
  const notifications = computed(() => store.getters.notifications || [])
  const hasNotifications = computed(() => store.getters.hasNotifications || false)
  
  // Methods
  const showNotification = (notification) => {
    const notificationWithId = {
      id: Date.now() + Math.random(),
      timeout: 5000, // 5 segundos por defecto
      ...notification
    }
    
    store.dispatch('showNotification', notificationWithId)
    return notificationWithId.id
  }
  
  const removeNotification = (id) => {
    store.commit('REMOVE_NOTIFICATION', id)
  }
  
  const clearAllNotifications = () => {
    store.commit('CLEAR_NOTIFICATIONS')
  }
  
  // Métodos de conveniencia para diferentes tipos de notificaciones
  const showSuccess = (message, options = {}) => {
    return showNotification({
      type: 'success',
      message,
      ...options
    })
  }
  
  const showError = (message, options = {}) => {
    return showNotification({
      type: 'error',
      message,
      timeout: 8000, // Errores duran más tiempo
      ...options
    })
  }
  
  const showWarning = (message, options = {}) => {
    return showNotification({
      type: 'warning',
      message,
      ...options
    })
  }
  
  const showInfo = (message, options = {}) => {
    return showNotification({
      type: 'info',
      message,
      ...options
    })
  }
  
  return {
    // State
    notifications,
    hasNotifications,
    
    // Methods
    showNotification,
    removeNotification,
    clearAllNotifications,
    
    // Convenience methods
    showSuccess,
    showError,
    showWarning,
    showInfo
  }
} 