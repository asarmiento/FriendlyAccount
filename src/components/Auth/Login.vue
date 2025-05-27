<template>
  <div class="login-container">
    <div class="container">
      <div class="row justify-center items-center min-h-screen login-row">
        <div class="col-12 sm:col-8 md:col-6 lg:col-4">
          <div class="login-card">
            <!-- Logo Header -->
            <div class="login-header text-center">
              <img src="@/assets/img/logo-sa.jpg" alt="Logo" class="login-logo">
              <h2 class="login-title">Friendly Account</h2>
              <p class="login-subtitle">Inicia sesión en tu cuenta</p>
            </div>

            <!-- Login Form -->
            <div class="login-body">
              <!-- Mostrar errores de validación -->
              <div v-if="errors.length > 0" class="alert alert-error">
                <div class="alert-content">
                  <strong>¡Ups!</strong> Hubo algunos problemas con tu entrada.
                  <ul class="error-list">
                    <li v-for="error in errors" :key="error">{{ error }}</li>
                  </ul>
                </div>
              </div>

              <form @submit.prevent="handleLogin" class="login-form">
                <!-- Campo Email -->
                <div class="form-group">
                  <label class="form-label">
                    <strong>Correo Electrónico</strong>
                  </label>
                  <input 
                    type="email" 
                    class="form-control" 
                    v-model="form.email"
                    :class="{ 'error': fieldErrors.email }"
                    placeholder="tu@email.com"
                    required
                  >
                  <div v-if="fieldErrors.email" class="field-error">
                    {{ fieldErrors.email }}
                  </div>
                </div>

                <!-- Campo Contraseña -->
                <div class="form-group">
                  <label class="form-label">
                    <strong>Contraseña</strong>
                  </label>
                  <input 
                    type="password" 
                    class="form-control" 
                    v-model="form.password"
                    :class="{ 'error': fieldErrors.password }"
                    placeholder="Tu contraseña"
                    required
                  >
                  <div v-if="fieldErrors.password" class="field-error">
                    {{ fieldErrors.password }}
                  </div>
                </div>

                <!-- Campo Código -->
                <div class="form-group">
                  <label class="form-label">
                    <strong>Código de Institución</strong>
                  </label>
                  <input 
                    type="password" 
                    class="form-control" 
                    v-model="form.session"
                    :class="{ 'error': fieldErrors.session }"
                    placeholder="Código de tu institución"
                    required
                  >
                  <div v-if="fieldErrors.session" class="field-error">
                    {{ fieldErrors.session }}
                  </div>
                </div>

                <!-- Remember Me -->
                <div class="form-group">
                  <label class="checkbox-label">
                    <input type="checkbox" v-model="form.remember" class="checkbox-input"> 
                    <span class="checkbox-text">Recordarme</span>
                  </label>
                </div>

                <!-- Botón Submit -->
                <div class="form-group">
                  <button 
                    type="submit" 
                    class="btn btn-primary w-full btn-lg"
                    :disabled="isLoading"
                  >
                    <span v-if="isLoading" class="loading-spinner"></span>
                    {{ isLoading ? 'Iniciando sesión...' : 'Iniciar Sesión' }}
                  </button>
                </div>
              </form>
            </div>

            <!-- Enlaces adicionales -->
            <div class="login-footer">
              <div class="footer-links">
                <a href="/auth/restaurant" class="footer-link">Restaurante</a>
                <a href="/password/email" class="footer-link">¿Olvidaste tu contraseña?</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

export default {
  name: 'Login',
  setup() {
    const store = useStore()
    const router = useRouter()
    
    const form = reactive({
      email: '',
      password: '',
      session: '',
      remember: false
    })
    
    const errors = ref([])
    const fieldErrors = ref({})
    const isLoading = ref(false)
    
    const validateForm = () => {
      const newErrors = {}
      errors.value = []
      
      if (!form.email) {
        newErrors.email = 'El email es requerido'
      } else if (!/\S+@\S+\.\S+/.test(form.email)) {
        newErrors.email = 'El email no tiene un formato válido'
      }
      
      if (!form.password) {
        newErrors.password = 'La contraseña es requerida'
      } else if (form.password.length < 6) {
        newErrors.password = 'La contraseña debe tener al menos 6 caracteres'
      }
      
      if (!form.session) {
        newErrors.session = 'El código es requerido'
      }
      
      fieldErrors.value = newErrors
      return Object.keys(newErrors).length === 0
    }
    
    const handleLogin = async () => {
      if (!validateForm()) {
        errors.value = Object.values(fieldErrors.value)
        return
      }
      
      isLoading.value = true
      errors.value = []
      fieldErrors.value = {}
      
      try {
        const result = await store.dispatch('auth/login', form)
        
        if (result.success) {
          // Redirigir a la pantalla de instituciones
          router.push('/schools')
        } else {
          // Manejar errores del backend de Laravel
          if (result.error.errors) {
            // Errores de validación del backend
            fieldErrors.value = result.error.errors
            errors.value = Object.values(result.error.errors).flat()
          } else if (result.error.message) {
            // Error general
            errors.value = [result.error.message]
          } else {
            // Fallback para otros errores
            errors.value = ['Error en el inicio de sesión']
          }
        }
      } catch (error) {
        console.error('Error durante el login:', error)
        errors.value = ['Error inesperado. Por favor, intenta de nuevo.']
      } finally {
        isLoading.value = false
      }
    }
    
    return {
      form,
      errors,
      fieldErrors,
      isLoading,
      handleLogin
    }
  }
}
</script>

<style scoped>
.login-container {
  min-height: 100vh;
  width: 2040px;
  background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
  position: relative;
  overflow: hidden;
}

.login-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
  opacity: 0.1;
}

.login-row {
  padding: var(--spacing-8) 0;
  position: relative;
  z-index: 1;
}

.login-card {
  background: var(--white);
  border-radius: var(--border-radius-xl);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
  overflow: hidden;
  border: 1px solid rgba(255, 255, 255, 0.2);
  max-width: 450px;
  width: 100%;
  margin: 0 auto;
  position: relative;
  z-index: 1;
  backdrop-filter: blur(10px);
}

.login-header {
  padding: var(--spacing-10) var(--spacing-8) var(--spacing-8);
  border-bottom: 1px solid var(--gray-200);
  background: linear-gradient(135deg, var(--gray-50) 0%, var(--white) 100%);
}

.login-logo {
  max-width: 120px;
  height: auto;
  border-radius: var(--border-radius);
  box-shadow: var(--shadow-md);
}

.login-title {
  font-size: var(--font-size-2xl);
  font-weight: 700;
  color: var(--gray-800);
  margin-top: var(--spacing-4);
  margin-bottom: var(--spacing-2);
}

.login-subtitle {
  font-size: var(--font-size-base);
  color: var(--gray-600);
  margin-bottom: 0;
}

.login-body {
  padding: var(--spacing-8) var(--spacing-8) var(--spacing-6);
}

.login-form {
  width: 100%;
}

.form-group {
  margin-bottom: var(--spacing-5);
}

.form-label {
  display: block;
  margin-bottom: var(--spacing-2);
  font-weight: 600;
  color: var(--gray-700);
  font-size: var(--font-size-sm);
}

.form-control {
  width: 100%;
  padding: var(--spacing-3);
  border: 1px solid var(--gray-300);
  border-radius: var(--border-radius);
  font-size: var(--font-size-base);
  background-color: var(--white);
  transition: all var(--transition-base);
}

.form-control:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.error {
  border-color: var(--error-color) !important;
  box-shadow: 0 0 0 3px rgba(245, 101, 101, 0.1) !important;
}

.field-error {
  display: block;
  color: var(--error-color);
  font-size: var(--font-size-sm);
  margin-top: var(--spacing-1);
  font-weight: 500;
}

.checkbox-label {
  display: flex;
  align-items: center;
  font-weight: 500;
  color: var(--gray-700);
  cursor: pointer;
  font-size: var(--font-size-sm);
}

.checkbox-input {
  margin-right: var(--spacing-2);
  width: 16px;
  height: 16px;
  accent-color: var(--primary-color);
}

.checkbox-text {
  user-select: none;
}

.btn-primary {
  background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
  border: none;
  border-radius: var(--border-radius);
  padding: var(--spacing-4) var(--spacing-6);
  font-weight: 600;
  font-size: var(--font-size-base);
  transition: all var(--transition-base);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 48px;
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: var(--shadow-lg);
  background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
}

.btn-primary:disabled {
  opacity: 0.7;
  transform: none;
  cursor: not-allowed;
}

.loading-spinner {
  display: inline-block;
  width: 1rem;
  height: 1rem;
  margin-right: 8px;
  border: 2px solid transparent;
  border-top: 2px solid currentColor;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.alert-error {
  background-color: #f8d7da;
  border: 1px solid #f5c6cb;
  color: #721c24;
  padding: var(--spacing-4);
  border-radius: var(--border-radius);
  margin-bottom: var(--spacing-4);
}

.alert-content {
  font-size: var(--font-size-sm);
}

.error-list {
  margin: var(--spacing-2) 0 0 var(--spacing-4);
  padding: 0;
}

.error-list li {
  margin-bottom: var(--spacing-1);
}

.login-footer {
  padding: var(--spacing-5) var(--spacing-8) var(--spacing-6);
  border-top: 1px solid var(--gray-200);
  background-color: var(--gray-50);
}

.footer-links {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: var(--spacing-4);
}

@media (max-width: 640px) {
  .footer-links {
    flex-direction: column;
    gap: var(--spacing-3);
  }
}

.footer-link {
  color: var(--primary-color);
  text-decoration: none;
  font-size: var(--font-size-sm);
  font-weight: 500;
  transition: color var(--transition-base);
}

.footer-link:hover {
  color: var(--primary-dark);
  text-decoration: underline;
}

/* Responsive adjustments */
@media (max-width: 640px) {
  .login-row {
    padding: var(--spacing-4) 0;
  }
  
  .login-card {
    max-width: 100%;
    margin: 0 var(--spacing-4);
  }
  
  .login-header {
    padding: var(--spacing-6) var(--spacing-6) var(--spacing-5);
  }
  
  .login-body {
    padding: var(--spacing-6) var(--spacing-6) var(--spacing-4);
  }
  
  .login-footer {
    padding: var(--spacing-4) var(--spacing-6) var(--spacing-5);
  }
  
  .login-logo {
    max-width: 100px;
  }
  
  .login-title {
    font-size: var(--font-size-xl);
  }
}

@media (max-width: 480px) {
  .login-card {
    margin: 0 var(--spacing-3);
  }
  
  .login-header {
    padding: var(--spacing-5) var(--spacing-5) var(--spacing-4);
  }
  
  .login-body {
    padding: var(--spacing-5) var(--spacing-5) var(--spacing-3);
  }
  
  .login-footer {
    padding: var(--spacing-3) var(--spacing-5) var(--spacing-4);
  }
}
</style> 