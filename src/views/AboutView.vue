<template>
  <div class="about-page">
    <!-- Page Header -->
    <PageHeader 
      title="Acerca de Friendly Account"
      subtitle="Conoce más sobre nuestra plataforma de gestión educativa"
      :breadcrumbs="breadcrumbs"
    >
      <template #actions>
        <router-link to="/login" class="btn btn-primary">
          Comenzar Ahora
        </router-link>
      </template>
    </PageHeader>

    <!-- Main Content -->
    <section class="about-content">
      <div class="container">
        <div class="row justify-center">
          <div class="col-12 lg:col-10">
            
            <!-- Introduction -->
            <div class="content-section">
              <div class="row items-center">
                <div class="col-12 md:col-8">
                  <h2 class="section-title">¿Qué es Friendly Account?</h2>
                  <p class="section-text">
                    Friendly Account es un sistema integral de gestión para instituciones educativas 
                    que permite administrar de manera eficiente todos los aspectos académicos y 
                    administrativos de tu institución. Diseñado con tecnología moderna y una 
                    interfaz intuitiva para facilitar el trabajo diario.
                  </p>
                </div>
                <div class="col-12 md:col-4">
                  <div class="intro-image">
                    <img src="@/assets/img/logo-sa.jpg" alt="Logo" class="logo-image">
                  </div>
                </div>
              </div>
            </div>

            <!-- Features Grid -->
            <div class="content-section">
              <h2 class="section-title text-center">Características Principales</h2>
              <div class="row">
                <div class="col-12 md:col-6 lg:col-4">
                  <div class="feature-item">
                    <div class="feature-icon">👨‍🎓</div>
                    <h3 class="feature-title">Gestión de Estudiantes</h3>
                    <p class="feature-text">Control completo de estudiantes y profesores con perfiles detallados.</p>
                  </div>
                </div>
                
                <div class="col-12 md:col-6 lg:col-4">
                  <div class="feature-item">
                    <div class="feature-icon">📊</div>
                    <h3 class="feature-title">Calificaciones y Asistencia</h3>
                    <p class="feature-text">Sistema avanzado para el control de notas y seguimiento de asistencia.</p>
                  </div>
                </div>
                
                <div class="col-12 md:col-6 lg:col-4">
                  <div class="feature-item">
                    <div class="feature-icon">💰</div>
                    <h3 class="feature-title">Administración Financiera</h3>
                    <p class="feature-text">Gestión completa de pagos, facturación y control financiero.</p>
                  </div>
                </div>
                
                <div class="col-12 md:col-6 lg:col-4">
                  <div class="feature-item">
                    <div class="feature-icon">📈</div>
                    <h3 class="feature-title">Reportes y Estadísticas</h3>
                    <p class="feature-text">Análisis detallados y reportes personalizables para toma de decisiones.</p>
                  </div>
                </div>
                
                <div class="col-12 md:col-6 lg:col-4">
                  <div class="feature-item">
                    <div class="feature-icon">👨‍👩‍👧‍👦</div>
                    <h3 class="feature-title">Comunicación Familiar</h3>
                    <p class="feature-text">Plataforma de comunicación directa con padres de familia.</p>
                  </div>
                </div>
                
                <div class="col-12 md:col-6 lg:col-4">
                  <div class="feature-item">
                    <div class="feature-icon">🏫</div>
                    <h3 class="feature-title">Gestión de Horarios</h3>
                    <p class="feature-text">Administración eficiente de horarios, aulas y recursos.</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Mission Section -->
            <div class="content-section">
              <div class="mission-card">
                <div class="row items-center">
                  <div class="col-12 lg:col-8">
                    <h2 class="section-title">Nuestra Misión</h2>
                    <p class="section-text">
                      Facilitar la gestión educativa mediante herramientas tecnológicas modernas 
                      y fáciles de usar, permitiendo que las instituciones se enfoquen en lo más 
                      importante: brindar una educación de calidad que transforme vidas y construya 
                      un futuro mejor para todos.
                    </p>
                  </div>
                  <div class="col-12 lg:col-4">
                    <div class="mission-stats">
                      <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Instituciones</div>
                      </div>
                      <div class="stat-item">
                        <div class="stat-number">50K+</div>
                        <div class="stat-label">Estudiantes</div>
                      </div>
                      <div class="stat-item">
                        <div class="stat-number">99%</div>
                        <div class="stat-label">Satisfacción</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- CTA Section -->
            <div class="content-section">
              <div class="cta-card text-center">
                <h2 class="cta-title">¿Listo para comenzar?</h2>
                <p class="cta-text">
                  Únete a cientos de instituciones que ya confían en Friendly Account 
                  para gestionar sus procesos educativos.
                </p>
                <div class="cta-actions">
                  <router-link to="/login" class="btn btn-primary btn-lg">
                    Comenzar Ahora
                  </router-link>
                  <router-link to="/" class="btn btn-outline btn-lg">
                    Ver Demo
                  </router-link>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import PageHeader from '@/components/common/PageHeader.vue'

const breadcrumbs = [
  { text: 'Inicio', to: '/' },
  { text: 'Acerca de' }
]
</script>

<style scoped>
.about-page {
  width: 100%;
}

.about-content {
  padding: var(--spacing-16) 0;
  background-color: var(--gray-50);
}

.content-section {
  margin-bottom: var(--spacing-16);
}

.content-section:last-child {
  margin-bottom: 0;
}

/* Introduction Section */
.intro-image {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: var(--spacing-6);
}

.logo-image {
  max-width: 200px;
  height: auto;
  border-radius: var(--border-radius-xl);
  box-shadow: var(--shadow-lg);
}

.section-title {
  font-size: var(--font-size-3xl);
  font-weight: 700;
  color: var(--gray-800);
  margin-bottom: var(--spacing-4);
}

@media (max-width: 768px) {
  .section-title {
    font-size: var(--font-size-2xl);
  }
}

.section-text {
  font-size: var(--font-size-lg);
  color: var(--gray-600);
  line-height: 1.7;
  margin-bottom: var(--spacing-6);
}

/* Features Grid */
.feature-item {
  background: var(--white);
  border-radius: var(--border-radius-lg);
  padding: var(--spacing-6);
  text-align: center;
  box-shadow: var(--shadow);
  transition: all var(--transition-base);
  height: 100%;
  margin-bottom: var(--spacing-6);
  border: 1px solid var(--gray-100);
}

.feature-item:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow-lg);
  border-color: var(--primary-color);
}

.feature-icon {
  font-size: 3rem;
  margin-bottom: var(--spacing-4);
  display: block;
}

.feature-title {
  font-size: var(--font-size-lg);
  font-weight: 600;
  color: var(--gray-800);
  margin-bottom: var(--spacing-3);
}

.feature-text {
  color: var(--gray-600);
  line-height: 1.6;
  font-size: var(--font-size-base);
}

/* Mission Section */
.mission-card {
  background: var(--white);
  border-radius: var(--border-radius-xl);
  padding: var(--spacing-12);
  box-shadow: var(--shadow-lg);
  border: 1px solid var(--gray-100);
}

.mission-stats {
  display: flex;
  justify-content: space-around;
  gap: var(--spacing-4);
  margin-top: var(--spacing-6);
}

@media (max-width: 1024px) {
  .mission-stats {
    margin-top: var(--spacing-8);
  }
}

.stat-item {
  text-align: center;
}

.stat-number {
  font-size: var(--font-size-3xl);
  font-weight: 700;
  color: var(--primary-color);
  margin-bottom: var(--spacing-1);
}

.stat-label {
  font-size: var(--font-size-sm);
  color: var(--gray-600);
  font-weight: 500;
}

/* CTA Section */
.cta-card {
  background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
  color: var(--white);
  border-radius: var(--border-radius-xl);
  padding: var(--spacing-12);
  position: relative;
  overflow: hidden;
}

.cta-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
}

.cta-title {
  font-size: var(--font-size-3xl);
  font-weight: 700;
  margin-bottom: var(--spacing-4);
  position: relative;
  z-index: 1;
}

@media (max-width: 768px) {
  .cta-title {
    font-size: var(--font-size-2xl);
  }
}

.cta-text {
  font-size: var(--font-size-lg);
  margin-bottom: var(--spacing-8);
  opacity: 0.95;
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
  position: relative;
  z-index: 1;
}

.cta-actions {
  display: flex;
  gap: var(--spacing-4);
  justify-content: center;
  flex-wrap: wrap;
  position: relative;
  z-index: 1;
}

/* Responsive adjustments */
@media (max-width: 1024px) {
  .about-content {
    padding: var(--spacing-12) 0;
  }
  
  .content-section {
    margin-bottom: var(--spacing-12);
  }
  
  .mission-card {
    padding: var(--spacing-8);
  }
  
  .cta-card {
    padding: var(--spacing-8);
  }
}

@media (max-width: 768px) {
  .about-content {
    padding: var(--spacing-8) 0;
  }
  
  .content-section {
    margin-bottom: var(--spacing-8);
  }
  
  .mission-card {
    padding: var(--spacing-6);
  }
  
  .cta-card {
    padding: var(--spacing-6);
  }
  
  .mission-stats {
    flex-direction: column;
    gap: var(--spacing-6);
  }
  
  .cta-actions {
    flex-direction: column;
    align-items: stretch;
    max-width: 300px;
    margin-left: auto;
    margin-right: auto;
  }
  
  .intro-image {
    margin-top: var(--spacing-6);
  }
}
</style>
