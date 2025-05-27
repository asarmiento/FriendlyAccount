<template>
  <header class="main-header">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container-fluid">
        <!-- Logo -->
        <router-link to="/schools" class="navbar-brand">
          <img src="@/assets/img/logo-sa.jpg" alt="Logo" class="logo">
          Friendly Account
        </router-link>

        <!-- Toggle button for mobile -->
        <button 
          class="navbar-toggler" 
          type="button" 
          @click="toggleMobileMenu"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation menu -->
        <div class="navbar-collapse" :class="{ 'show': showMobileMenu }">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <router-link 
                to="/schools" 
                class="nav-link"
                :class="{ active: $route.path === '/schools' }"
              >
                <i class="fa fa-university"></i>
                Instituciones
              </router-link>
            </li>
            <!-- Agregar más elementos de navegación aquí -->
          </ul>

          <!-- User menu -->
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a 
                class="nav-link dropdown-toggle" 
                href="#" 
                @click="toggleUserMenu"
                role="button"
              >
                <i class="fa fa-user"></i>
                {{ user?.name || 'Usuario' }}
              </a>
              <ul class="dropdown-menu" :class="{ show: showUserMenu }">
                <li>
                  <a class="dropdown-item" href="#" @click="showProfile">
                    <i class="fa fa-user"></i>
                    Perfil
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="#" @click="showSettings">
                    <i class="fa fa-cog"></i>
                    Configuración
                  </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item" href="#" @click="handleLogout">
                    <i class="fa fa-sign-out"></i>
                    Cerrar Sesión
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
</template>

<script>
import { ref, computed } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

export default {
  name: 'Header',
  setup() {
    const store = useStore()
    const router = useRouter()
    
    const showMobileMenu = ref(false)
    const showUserMenu = ref(false)
    
    // Computed
    const user = computed(() => store.getters.user)
    
    // Methods
    const toggleMobileMenu = () => {
      showMobileMenu.value = !showMobileMenu.value
    }
    
    const toggleUserMenu = (event) => {
      event.preventDefault()
      showUserMenu.value = !showUserMenu.value
    }
    
    const showProfile = () => {
      showUserMenu.value = false
      // Implementar navegación al perfil
      console.log('Mostrar perfil')
    }
    
    const showSettings = () => {
      showUserMenu.value = false
      // Implementar navegación a configuración
      console.log('Mostrar configuración')
    }
    
    const handleLogout = async () => {
      if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
        await store.dispatch('auth/logout')
        router.push('/login')
      }
      showUserMenu.value = false
    }
    
    // Cerrar menús cuando se hace clic fuera
    const handleClickOutside = (event) => {
      if (!event.target.closest('.dropdown')) {
        showUserMenu.value = false
      }
      if (!event.target.closest('.navbar-toggler') && !event.target.closest('.navbar-collapse')) {
        showMobileMenu.value = false
      }
    }
    
    // Agregar listener para clics fuera
    document.addEventListener('click', handleClickOutside)
    
    return {
      user,
      showMobileMenu,
      showUserMenu,
      toggleMobileMenu,
      toggleUserMenu,
      showProfile,
      showSettings,
      handleLogout
    }
  }
}
</script>

<style scoped>
.main-header {
  position: sticky;
  top: 0;
  z-index: 1030;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.navbar {
  padding: 0.5rem 1rem;
}

.navbar-brand {
  display: flex;
  align-items: center;
  font-weight: 600;
  color: white !important;
  text-decoration: none;
}

.navbar-brand:hover {
  color: #f8f9fa !important;
}

.logo {
  height: 40px;
  width: auto;
  margin-right: 10px;
  border-radius: 4px;
}

.nav-link {
  color: rgba(255, 255, 255, 0.85) !important;
  font-weight: 500;
  padding: 0.75rem 1rem !important;
  border-radius: 4px;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
}

.nav-link i {
  margin-right: 8px;
  font-size: 1.1em;
}

.nav-link:hover,
.nav-link.active {
  color: white !important;
  background-color: rgba(255, 255, 255, 0.1);
}

.dropdown {
  position: relative;
}

.dropdown-toggle {
  cursor: pointer;
}

.dropdown-toggle::after {
  margin-left: 8px;
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  right: 0;
  background: white;
  border: 1px solid #e9ecef;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  padding: 0.5rem 0;
  min-width: 200px;
  opacity: 0;
  visibility: hidden;
  transform: translateY(-10px);
  transition: all 0.3s ease;
}

.dropdown-menu.show {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

.dropdown-item {
  display: flex;
  align-items: center;
  padding: 0.75rem 1.5rem;
  color: #333;
  text-decoration: none;
  transition: background-color 0.3s ease;
  border: none;
  background: none;
  width: 100%;
  text-align: left;
}

.dropdown-item i {
  margin-right: 10px;
  width: 16px;
  color: #6c757d;
}

.dropdown-item:hover {
  background-color: #f8f9fa;
  color: #333;
}

.dropdown-divider {
  height: 0;
  margin: 0.5rem 0;
  overflow: hidden;
  border-top: 1px solid #e9ecef;
}

.navbar-toggler {
  border: none;
  padding: 4px 8px;
}

.navbar-toggler:focus {
  box-shadow: none;
}

/* Responsive */
@media (max-width: 991.98px) {
  .navbar-collapse {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #007bff;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    padding: 1rem;
    display: none;
  }
  
  .navbar-collapse.show {
    display: block;
  }
  
  .navbar-nav {
    flex-direction: column;
  }
  
  .nav-item {
    width: 100%;
  }
  
  .dropdown-menu {
    position: static;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    box-shadow: none;
    opacity: 1;
    visibility: visible;
    transform: none;
    margin-top: 0.5rem;
  }
  
  .dropdown-item {
    color: white;
  }
  
  .dropdown-item:hover {
    background-color: rgba(255, 255, 255, 0.1);
    color: white;
  }
  
  .dropdown-divider {
    border-top-color: rgba(255, 255, 255, 0.2);
  }
}
</style> 