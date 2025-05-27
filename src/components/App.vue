<template>
  <div id="app">
    <!-- Sistema de notificaciones global -->
    <NotificationContainer v-if="notifications.length > 0" />
    
    <!-- Vista principal de la aplicación -->
    <router-view />
  </div>
</template>

<script>
import { onMounted, computed } from 'vue'
import { useAuth } from '../composables/useAuth'
import { useNotifications } from '../composables/useNotifications'
import { useRoute, useRouter } from 'vue-router'
import NotificationContainer from './UI/NotificationContainer.vue'

export default {
  name: 'App',
  components: {
    NotificationContainer
  },
  setup() {
    const { checkAuth, isAuthenticated } = useAuth()
    const { notifications } = useNotifications()
    const route = useRoute()
    const router = useRouter()
    
    // Remover la verificación automática de autenticación en App.vue
    // ya que esto puede interferir con el flujo normal de login
    // La autenticación se maneja a través de los middlewares del router
    
    return {
      notifications
    }
  }
}
</script>

<style>
/* Reset y estilos globales */
* {
  box-sizing: border-box;
}

body {
  margin: 0;
  padding: 0;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #f8f9fa;
  color: #333;
  line-height: 1.6;
}

#app {
  min-height: 100vh;
  position: relative;
}

/* Clases utilitarias globales */
.text-center {
  text-align: center;
}

.text-left {
  text-align: left;
}

.text-right {
  text-align: right;
}

.mt-2 {
  margin-top: 0.5rem;
}

.mt-3 {
  margin-top: 1rem;
}

.mb-2 {
  margin-bottom: 0.5rem;
}

.mb-3 {
  margin-bottom: 1rem;
}

.p-2 {
  padding: 0.5rem;
}

.p-3 {
  padding: 1rem;
}

/* Estilos para botones */
.btn {
  display: inline-block;
  padding: 0.375rem 0.75rem;
  margin-bottom: 0;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  text-align: center;
  text-decoration: none;
  vertical-align: middle;
  cursor: pointer;
  border: 1px solid transparent;
  border-radius: 0.25rem;
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.btn-primary {
  color: #fff;
  background-color: #007bff;
  border-color: #007bff;
}

.btn-primary:hover {
  color: #fff;
  background-color: #0056b3;
  border-color: #004085;
}

.btn-success {
  color: #fff;
  background-color: #28a745;
  border-color: #28a745;
}

.btn-success:hover {
  color: #fff;
  background-color: #1e7e34;
  border-color: #1c7430;
}

.btn-danger {
  color: #fff;
  background-color: #dc3545;
  border-color: #dc3545;
}

.btn-danger:hover {
  color: #fff;
  background-color: #c82333;
  border-color: #bd2130;
}

/* Estilos para alertas */
.alert {
  position: relative;
  padding: 0.75rem 1.25rem;
  margin-bottom: 1rem;
  border: 1px solid transparent;
  border-radius: 0.25rem;
}

.alert-danger {
  color: #721c24;
  background-color: #f8d7da;
  border-color: #f5c6cb;
}

.alert-success {
  color: #155724;
  background-color: #d4edda;
  border-color: #c3e6cb;
}

.alert-warning {
  color: #856404;
  background-color: #fff3cd;
  border-color: #ffeaa7;
}

.alert-info {
  color: #0c5460;
  background-color: #d1ecf1;
  border-color: #bee5eb;
}

/* Responsive design */
@media (max-width: 768px) {
  .btn {
    width: 100%;
    margin-bottom: 0.5rem;
  }
}

/* Animaciones */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

/* Loading spinner */
.spinner-border {
  display: inline-block;
  width: 2rem;
  height: 2rem;
  vertical-align: text-bottom;
  border: 0.25em solid currentColor;
  border-right-color: transparent;
  border-radius: 50%;
  animation: spinner-border 0.75s linear infinite;
}

.spinner-border-sm {
  width: 1rem;
  height: 1rem;
  border-width: 0.125em;
}

@keyframes spinner-border {
  to {
    transform: rotate(360deg);
  }
}
</style>
