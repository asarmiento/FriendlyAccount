<template>
  <div class="home-page">
    <!-- Hero Section -->
    <section class="hero-section">
      <div class="container">
        <div class="row items-center min-h-screen">
          <div class="col-12 lg:col-6">
            <div class="hero-content">
              <h1 class="hero-title">
                Bienvenido a 
                <span class="text-primary">Friendly Account</span>
              </h1>
              <p class="hero-subtitle">
                Sistema integral de gestión para instituciones educativas. 
                Simplifica la administración, mejora la comunicación y optimiza los procesos académicos.
              </p>
              
              <div class="hero-actions">
                <router-link to="/login" class="btn btn-primary btn-lg">
                  Iniciar Sesión
                </router-link>
                <router-link to="/about" class="btn btn-outline btn-lg">
                  Conoce Más
                </router-link>
              </div>

              <!-- Características destacadas -->
              <div class="features-preview">
                <div class="feature-item">
                  <div class="feature-icon">📊</div>
                  <span>Gestión Académica</span>
                </div>
                <div class="feature-item">
                  <div class="feature-icon">👥</div>
                  <span>Administración de Usuarios</span>
                </div>
                <div class="feature-item">
                  <div class="feature-icon">📈</div>
                  <span>Reportes y Analytics</span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-12 lg:col-6">
            <div class="hero-image">
              <img alt="Friendly Account Dashboard" src="@/assets/img/logo-sa.jpg" class="dashboard-preview" />
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
      <div class="container">
        <div class="section-header text-center">
          <h2 class="section-title">Características Principales</h2>
          <p class="section-subtitle">
            Todo lo que necesitas para gestionar tu institución educativa de manera eficiente
          </p>
        </div>

        <div class="row">
          <div class="col-12 md:col-6 lg:col-4">
            <div class="feature-card">
              <div class="feature-card-icon">🏫</div>
              <h3 class="feature-card-title">Gestión de Escuelas</h3>
              <p class="feature-card-text">
                Administra múltiples instituciones desde una sola plataforma con herramientas especializadas.
              </p>
            </div>
          </div>

          <div class="col-12 md:col-6 lg:col-4">
            <div class="feature-card">
              <div class="feature-card-icon">👨‍🎓</div>
              <h3 class="feature-card-title">Control de Estudiantes</h3>
              <p class="feature-card-text">
                Registro completo de estudiantes, calificaciones, asistencia y seguimiento académico.
              </p>
            </div>
          </div>

          <div class="col-12 md:col-6 lg:col-4">
            <div class="feature-card">
              <div class="feature-card-icon">💰</div>
              <h3 class="feature-card-title">Gestión Financiera</h3>
              <p class="feature-card-text">
                Control de pagos, facturación, becas y toda la gestión económica de la institución.
              </p>
            </div>
          </div>

          <div class="col-12 md:col-6 lg:col-4">
            <div class="feature-card">
              <div class="feature-card-icon">📚</div>
              <h3 class="feature-card-title">Gestión Académica</h3>
              <p class="feature-card-text">
                Planificación de cursos, horarios, evaluaciones y seguimiento del progreso académico.
              </p>
            </div>
          </div>

          <div class="col-12 md:col-6 lg:col-4">
            <div class="feature-card">
              <div class="feature-card-icon">📊</div>
              <h3 class="feature-card-title">Reportes Avanzados</h3>
              <p class="feature-card-text">
                Genera reportes detallados y analytics para tomar decisiones informadas.
              </p>
            </div>
          </div>

          <div class="col-12 md:col-6 lg:col-4">
            <div class="feature-card">
              <div class="feature-card-icon">🔒</div>
              <h3 class="feature-card-title">Seguridad Total</h3>
              <p class="feature-card-text">
                Protección de datos con los más altos estándares de seguridad y privacidad.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
      <div class="container">
        <div class="cta-content text-center">
          <h2 class="cta-title">¿Listo para transformar tu institución?</h2>
          <p class="cta-subtitle">
            Únete a cientos de instituciones que ya confían en Friendly Account
          </p>
          <div class="cta-actions">
            <router-link to="/login" class="btn btn-primary btn-lg">
              Comenzar Ahora
            </router-link>
            <router-link to="/about" class="btn btn-outline btn-lg">
              Ver Demo
            </router-link>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'HomeView'
}
</script>

<style scoped>
.home-page {
  width: 100%;
}

/* Hero Section */
.hero-section {
  background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
  color: var(--white);
  padding: var(--spacing-20) 0;
  position: relative;
  overflow: hidden;
}

.hero-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
  opacity: 0.1;
}

.hero-content {
  position: relative;
  z-index: 1;
  padding: var(--spacing-8) 0;
}

.hero-title {
  font-size: var(--font-size-5xl);
  font-weight: 700;
  line-height: 1.2;
  margin-bottom: var(--spacing-6);
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

@media (max-width: 768px) {
  .hero-title {
    font-size: var(--font-size-3xl);
  }
}

.hero-subtitle {
  font-size: var(--font-size-xl);
  line-height: 1.6;
  margin-bottom: var(--spacing-8);
  opacity: 0.95;
  max-width: 500px;
}

@media (max-width: 768px) {
  .hero-subtitle {
    font-size: var(--font-size-lg);
  }
}

.hero-actions {
  display: flex;
  gap: var(--spacing-4);
  margin-bottom: var(--spacing-12);
  flex-wrap: wrap;
}

.hero-image {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: var(--spacing-8);
}

.dashboard-preview {
  max-width: 100%;
  height: auto;
  border-radius: var(--border-radius-xl);
  box-shadow: var(--shadow-xl);
  transform: perspective(1000px) rotateY(-5deg) rotateX(5deg);
  transition: transform var(--transition-slow);
}

.dashboard-preview:hover {
  transform: perspective(1000px) rotateY(0deg) rotateX(0deg);
}

/* Features Preview */
.features-preview {
  display: flex;
  gap: var(--spacing-6);
  flex-wrap: wrap;
}

@media (max-width: 768px) {
  .features-preview {
    gap: var(--spacing-4);
  }
}

.feature-item {
  display: flex;
  align-items: center;
  gap: var(--spacing-2);
  background: rgba(255, 255, 255, 0.1);
  padding: var(--spacing-3) var(--spacing-4);
  border-radius: var(--border-radius-full);
  backdrop-filter: blur(10px);
  font-size: var(--font-size-sm);
  font-weight: 500;
}

.feature-icon {
  font-size: var(--font-size-lg);
}

/* Features Section */
.features-section {
  padding: var(--spacing-20) 0;
  background-color: var(--white);
}

.section-header {
  margin-bottom: var(--spacing-16);
}

.section-title {
  font-size: var(--font-size-4xl);
  font-weight: 700;
  color: var(--gray-800);
  margin-bottom: var(--spacing-4);
}

@media (max-width: 768px) {
  .section-title {
    font-size: var(--font-size-3xl);
  }
}

.section-subtitle {
  font-size: var(--font-size-xl);
  color: var(--gray-600);
  max-width: 600px;
  margin: 0 auto;
  line-height: 1.6;
}

@media (max-width: 768px) {
  .section-subtitle {
    font-size: var(--font-size-lg);
  }
}

.feature-card {
  background: var(--white);
  border-radius: var(--border-radius-xl);
  padding: var(--spacing-8);
  text-align: center;
  box-shadow: var(--shadow);
  transition: all var(--transition-base);
  height: 100%;
  border: 1px solid var(--gray-100);
  margin-bottom: var(--spacing-6);
}

.feature-card:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow-lg);
  border-color: var(--primary-color);
}

.feature-card-icon {
  font-size: 3rem;
  margin-bottom: var(--spacing-4);
  display: block;
}

.feature-card-title {
  font-size: var(--font-size-xl);
  font-weight: 600;
  color: var(--gray-800);
  margin-bottom: var(--spacing-3);
}

.feature-card-text {
  color: var(--gray-600);
  line-height: 1.6;
}

/* CTA Section */
.cta-section {
  background: linear-gradient(135deg, var(--gray-800) 0%, var(--gray-900) 100%);
  color: var(--white);
  padding: var(--spacing-20) 0;
  position: relative;
}

.cta-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
}

.cta-content {
  position: relative;
  z-index: 1;
}

.cta-title {
  font-size: var(--font-size-4xl);
  font-weight: 700;
  margin-bottom: var(--spacing-4);
}

@media (max-width: 768px) {
  .cta-title {
    font-size: var(--font-size-3xl);
  }
}

.cta-subtitle {
  font-size: var(--font-size-xl);
  margin-bottom: var(--spacing-8);
  opacity: 0.9;
  max-width: 500px;
  margin-left: auto;
  margin-right: auto;
}

@media (max-width: 768px) {
  .cta-subtitle {
    font-size: var(--font-size-lg);
  }
}

.cta-actions {
  display: flex;
  gap: var(--spacing-4);
  justify-content: center;
  flex-wrap: wrap;
}

/* Responsive adjustments */
@media (max-width: 1024px) {
  .hero-section {
    padding: var(--spacing-16) 0;
  }
  
  .features-section {
    padding: var(--spacing-16) 0;
  }
  
  .cta-section {
    padding: var(--spacing-16) 0;
  }
}

@media (max-width: 768px) {
  .hero-section {
    padding: var(--spacing-12) 0;
  }
  
  .features-section {
    padding: var(--spacing-12) 0;
  }
  
  .cta-section {
    padding: var(--spacing-12) 0;
  }
  
  .section-header {
    margin-bottom: var(--spacing-12);
  }
  
  .hero-actions {
    flex-direction: column;
    align-items: stretch;
  }
  
  .cta-actions {
    flex-direction: column;
    align-items: stretch;
    max-width: 300px;
    margin: 0 auto;
  }
}
</style>
