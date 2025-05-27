<template>
  <div class="page-header">
    <div class="container">
      <div class="row items-center">
        <div class="col-12 md:col-8">
          <div class="header-content">
            <h1 class="page-title">{{ title }}</h1>
            <p v-if="subtitle" class="page-subtitle">{{ subtitle }}</p>
            
            <!-- Breadcrumbs -->
            <nav v-if="breadcrumbs && breadcrumbs.length" class="breadcrumbs">
              <ol class="breadcrumb-list">
                <li 
                  v-for="(item, index) in breadcrumbs" 
                  :key="index"
                  class="breadcrumb-item"
                  :class="{ 'active': index === breadcrumbs.length - 1 }"
                >
                  <router-link 
                    v-if="item.to && index !== breadcrumbs.length - 1" 
                    :to="item.to"
                    class="breadcrumb-link"
                  >
                    {{ item.text }}
                  </router-link>
                  <span v-else>{{ item.text }}</span>
                  <span v-if="index < breadcrumbs.length - 1" class="breadcrumb-separator">/</span>
                </li>
              </ol>
            </nav>
          </div>
        </div>
        
        <div class="col-12 md:col-4">
          <div class="header-actions">
            <slot name="actions" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true
  },
  subtitle: {
    type: String,
    default: ''
  },
  breadcrumbs: {
    type: Array,
    default: () => []
  }
})
</script>

<style scoped>
.page-header {
  background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
  color: var(--white);
  padding: var(--spacing-12) 0 var(--spacing-8);
  position: relative;
  overflow: hidden;
}

.page-header::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
}

.header-content {
  position: relative;
  z-index: 1;
}

.page-title {
  font-size: var(--font-size-4xl);
  font-weight: 700;
  margin-bottom: var(--spacing-3);
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
}

@media (max-width: 768px) {
  .page-title {
    font-size: var(--font-size-3xl);
  }
}

.page-subtitle {
  font-size: var(--font-size-lg);
  opacity: 0.9;
  margin-bottom: var(--spacing-4);
  line-height: 1.5;
}

.breadcrumbs {
  margin-top: var(--spacing-4);
}

.breadcrumb-list {
  display: flex;
  align-items: center;
  gap: var(--spacing-2);
  list-style: none;
  padding: 0;
  margin: 0;
  flex-wrap: wrap;
}

.breadcrumb-item {
  display: flex;
  align-items: center;
  gap: var(--spacing-2);
  font-size: var(--font-size-sm);
}

.breadcrumb-link {
  color: rgba(255, 255, 255, 0.8);
  text-decoration: none;
  transition: color var(--transition-base);
}

.breadcrumb-link:hover {
  color: var(--white);
}

.breadcrumb-item.active {
  color: var(--white);
  font-weight: 500;
}

.breadcrumb-separator {
  color: rgba(255, 255, 255, 0.6);
  margin: 0 var(--spacing-1);
}

.header-actions {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: var(--spacing-3);
  position: relative;
  z-index: 1;
}

@media (max-width: 768px) {
  .header-actions {
    justify-content: flex-start;
    margin-top: var(--spacing-4);
  }
  
  .page-header {
    padding: var(--spacing-8) 0 var(--spacing-6);
  }
}
</style> 