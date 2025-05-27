<template>
  <div class="notification-container">
    <transition-group name="notification" tag="div">
      <div
        v-for="notification in notifications"
        :key="notification.id"
        class="notification"
        :class="[`notification--${notification.type}`]"
      >
        <div class="notification__content">
          <div class="notification__icon">
            <i :class="getIconClass(notification.type)"></i>
          </div>
          <div class="notification__message">
            {{ notification.message }}
          </div>
          <button
            class="notification__close"
            @click="removeNotification(notification.id)"
          >
            <i class="fa fa-times"></i>
          </button>
        </div>
      </div>
    </transition-group>
  </div>
</template>

<script>
import { useNotifications } from '../../composables/useNotifications'

export default {
  name: 'NotificationContainer',
  setup() {
    const { notifications, removeNotification } = useNotifications()
    
    const getIconClass = (type) => {
      const icons = {
        success: 'fa fa-check-circle',
        error: 'fa fa-exclamation-circle',
        warning: 'fa fa-exclamation-triangle',
        info: 'fa fa-info-circle'
      }
      return icons[type] || icons.info
    }
    
    return {
      notifications,
      removeNotification,
      getIconClass
    }
  }
}
</script>

<style scoped>
.notification-container {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 9999;
  max-width: 400px;
}

.notification {
  background: white;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  margin-bottom: 10px;
  overflow: hidden;
  border-left: 4px solid #ccc;
}

.notification--success {
  border-left-color: #28a745;
}

.notification--error {
  border-left-color: #dc3545;
}

.notification--warning {
  border-left-color: #ffc107;
}

.notification--info {
  border-left-color: #17a2b8;
}

.notification__content {
  display: flex;
  align-items: center;
  padding: 16px;
}

.notification__icon {
  margin-right: 12px;
  font-size: 20px;
}

.notification--success .notification__icon {
  color: #28a745;
}

.notification--error .notification__icon {
  color: #dc3545;
}

.notification--warning .notification__icon {
  color: #ffc107;
}

.notification--info .notification__icon {
  color: #17a2b8;
}

.notification__message {
  flex: 1;
  font-size: 14px;
  line-height: 1.4;
  color: #333;
}

.notification__close {
  background: none;
  border: none;
  color: #999;
  cursor: pointer;
  font-size: 16px;
  padding: 4px;
  margin-left: 8px;
  border-radius: 4px;
  transition: all 0.2s ease;
}

.notification__close:hover {
  background: #f5f5f5;
  color: #666;
}

/* Animaciones */
.notification-enter-active,
.notification-leave-active {
  transition: all 0.3s ease;
}

.notification-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.notification-leave-to {
  opacity: 0;
  transform: translateX(100%);
}

.notification-move {
  transition: transform 0.3s ease;
}
</style> 