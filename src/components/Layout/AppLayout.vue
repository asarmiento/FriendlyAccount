<template>
  <div class="app-layout">
    <!-- Header/Navbar -->
    <header class="app-header">
      <nav class="navbar">
        <div class="container-fluid">
          <div class="navbar-content">
            <!-- Logo y título -->
            <div class="navbar-brand">
              <router-link to="/" class="brand-link">
                <img src="@/assets/img/logo-sa.jpg" alt="Logo" class="brand-logo" />
                <span class="brand-text">Friendly Account</span>
              </router-link>
            </div>

            <!-- Botón hamburguesa para móvil -->
            <button 
              class="mobile-menu-btn lg:d-none"
              @click="toggleMobileMenu"
              :class="{ 'active': isMobileMenuOpen }"
            >
              <span></span>
              <span></span>
              <span></span>
            </button>

            <!-- Navegación principal -->
            <div class="navbar-nav" :class="{ 'mobile-open': isMobileMenuOpen }">
              <router-link to="/" class="nav-link" @click="closeMobileMenu">
                <i class="icon-home"></i>
                <span>Inicio</span>
              </router-link>
              <router-link to="/schools" class="nav-link" @click="closeMobileMenu">
                <i class="icon-school"></i>
                <span>Escuelas</span>
              </router-link>
              <router-link to="/about" class="nav-link" @click="closeMobileMenu">
                <i class="icon-info"></i>
                <span>Acerca de</span>
              </router-link>
            </div>

            <!-- Acciones del usuario -->
            <div class="navbar-actions">
              <router-link to="/login" class="btn btn-outline btn-sm">
                Iniciar Sesión
              </router-link>
            </div>
          </div>
        </div>
      </nav>
    </header>

    <!-- Contenido principal -->
    <main class="app-main">
      <div class="main-content">
        <slot />
      </div>
    </main>

    <!-- Footer -->
    <footer class="app-footer">
      <div class="container">
        <div class="footer-content">
          <div class="row">
            <div class="col-12 md:col-6 lg:col-4">
              <div class="footer-section">
                <h4 class="footer-title">Friendly Account</h4>
                <p class="footer-text">
                  Sistema integral de gestión para instituciones educativas.
                </p>
              </div>
            </div>
            <div class="col-12 md:col-6 lg:col-4">
              <div class="footer-section">
                <h4 class="footer-title">Enlaces</h4>
                <ul class="footer-links">
                  <li><router-link to="/">Inicio</router-link></li>
                  <li><router-link to="/schools">Escuelas</router-link></li>
                  <li><router-link to="/about">Acerca de</router-link></li>
                </ul>
              </div>
            </div>
            <div class="col-12 lg:col-4">
              <div class="footer-section">
                <h4 class="footer-title">Contacto</h4>
                <p class="footer-text">
                  Email: info@friendlyaccount.com<br>
                  Teléfono: +1 (555) 123-4567
                </p>
              </div>
            </div>
          </div>
          <div class="footer-bottom">
            <p>&copy; 2024 Friendly Account. Todos los derechos reservados.</p>
          </div>
        </div>
      </div>
    </footer>

    <!-- Overlay para menú móvil -->
    <div 
      v-if="isMobileMenuOpen" 
      class="mobile-overlay"
      @click="closeMobileMenu"
    ></div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const isMobileMenuOpen = ref(false)

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
}

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false
}

// Cerrar menú móvil al cambiar tamaño de pantalla
const handleResize = () => {
  if (window.innerWidth >= 1024) {
    isMobileMenuOpen.value = false
  }
}

onMounted(() => {
  window.addEventListener('resize', handleResize)
})

onUnmounted(() => {
  window.removeEventListener('resize', handleResize)
})
</script>

<style scoped>
.app-layout {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

/* Header */
.app-header {
  background-color: var(--white);
  box-shadow: var(--shadow-md);
  position: sticky;
  top: 0;
  z-index: 1000;
}

.navbar {
  padding: var(--spacing-4) 0;
}

.navbar-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: var(--spacing-4);
}

.navbar-brand {
  flex-shrink: 0;
}

.brand-link {
  display: flex;
  align-items: center;
  gap: var(--spacing-3);
  text-decoration: none;
  color: var(--gray-800);
  font-weight: 600;
  font-size: var(--font-size-lg);
}

.brand-logo {
  width: 40px;
  height: 40px;
  border-radius: var(--border-radius);
  object-fit: cover;
}

.brand-text {
  display: none;
}

@media (min-width: 640px) {
  .brand-text {
    display: block;
  }
}

/* Botón hamburguesa */
.mobile-menu-btn {
  display: flex;
  flex-direction: column;
  justify-content: space-around;
  width: 30px;
  height: 30px;
  background: transparent;
  border: none;
  cursor: pointer;
  padding: 0;
  z-index: 1001;
}

.mobile-menu-btn span {
  width: 100%;
  height: 3px;
  background-color: var(--gray-700);
  border-radius: 2px;
  transition: all var(--transition-base);
  transform-origin: center;
}

.mobile-menu-btn.active span:nth-child(1) {
  transform: rotate(45deg) translate(7px, 7px);
}

.mobile-menu-btn.active span:nth-child(2) {
  opacity: 0;
}

.mobile-menu-btn.active span:nth-child(3) {
  transform: rotate(-45deg) translate(7px, -7px);
}

/* Navegación */
.navbar-nav {
  display: flex;
  align-items: center;
  gap: var(--spacing-6);
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh;
  background-color: var(--white);
  flex-direction: column;
  justify-content: center;
  transform: translateX(-100%);
  transition: transform var(--transition-base);
  z-index: 999;
}

.navbar-nav.mobile-open {
  transform: translateX(0);
}

@media (min-width: 1024px) {
  .navbar-nav {
    position: static;
    width: auto;
    height: auto;
    background: transparent;
    flex-direction: row;
    justify-content: flex-start;
    transform: none;
    transition: none;
  }
}

.nav-link {
  display: flex;
  align-items: center;
  gap: var(--spacing-2);
  padding: var(--spacing-3) var(--spacing-4);
  text-decoration: none;
  color: var(--gray-600);
  font-weight: 500;
  border-radius: var(--border-radius);
  transition: all var(--transition-base);
  font-size: var(--font-size-lg);
}

@media (min-width: 1024px) {
  .nav-link {
    font-size: var(--font-size-base);
  }
}

.nav-link:hover {
  color: var(--primary-color);
  background-color: var(--gray-50);
}

.nav-link.router-link-active {
  color: var(--primary-color);
  background-color: rgba(102, 126, 234, 0.1);
}

/* Iconos simples con CSS */
.nav-link i {
  width: 20px;
  height: 20px;
  position: relative;
}

.icon-home::before {
  content: "🏠";
}

.icon-school::before {
  content: "🏫";
}

.icon-info::before {
  content: "ℹ️";
}

/* Acciones del navbar */
.navbar-actions {
  display: none;
}

@media (min-width: 1024px) {
  .navbar-actions {
    display: flex;
    align-items: center;
    gap: var(--spacing-3);
  }
}

/* Overlay móvil */
.mobile-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 998;
}

@media (min-width: 1024px) {
  .mobile-overlay {
    display: none;
  }
}

/* Contenido principal */
.app-main {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.main-content {
  flex: 1;
  width: 100%;
}

/* Footer */
.app-footer {
  background-color: var(--gray-800);
  color: var(--white);
  margin-top: auto;
}

.footer-content {
  padding: var(--spacing-12) 0 var(--spacing-6);
}

.footer-section {
  margin-bottom: var(--spacing-8);
}

@media (min-width: 768px) {
  .footer-section {
    margin-bottom: var(--spacing-6);
  }
}

.footer-title {
  font-size: var(--font-size-lg);
  font-weight: 600;
  margin-bottom: var(--spacing-4);
  color: var(--white);
}

.footer-text {
  color: var(--gray-300);
  line-height: 1.6;
}

.footer-links {
  list-style: none;
  padding: 0;
}

.footer-links li {
  margin-bottom: var(--spacing-2);
}

.footer-links a {
  color: var(--gray-300);
  text-decoration: none;
  transition: color var(--transition-base);
}

.footer-links a:hover {
  color: var(--white);
}

.footer-bottom {
  border-top: 1px solid var(--gray-700);
  padding-top: var(--spacing-6);
  text-align: center;
  color: var(--gray-400);
}
</style> 