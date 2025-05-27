<template>
  <div class="schools-container">
    <!-- Header con navegación -->
    <Header />
    
    <!-- Página breadcrumb -->
    <aside class="page">
      <h2>Institución</h2>
      <div class="list-inline-block">
        <ul>
          <li><router-link to="/">Home</router-link></li>
          <li><a>Institución</a></li>
          <li class="active-page"><a>Ver Institución</a></li>
        </ul>
      </div>
    </aside>

    <!-- Contenido principal -->
    <div class="paddingWrapper">
      <!-- Botón de creación solo para Super Administrador -->
      <div v-if="canCreateAccounts" class="row" style="margin: 5px">
        <button @click="openCreateModal" class="btn btn-success">
          Creación Nueva Cuenta
        </button>
      </div>

      <!-- Loading spinner -->
      <div v-if="isLoading" class="loading-container">
        <div class="spinner-border text-primary" role="status">
          <span class="sr-only">Cargando...</span>
        </div>
        <p>Cargando instituciones...</p>
      </div>

      <!-- Error message -->
      <div v-else-if="error" class="alert alert-danger text-center">
        {{ error }}
        <br>
        <button @click="loadSchools" class="btn btn-primary mt-2">
          Intentar de nuevo
        </button>
      </div>

      <!-- Lista de instituciones en formato thumbnail -->
      <div v-else class="row paddingWrapper">
        <div v-if="hasSchools">
          <div v-for="school in schools" :key="school.id" class="col-sm-6 col-md-4">
            <div class="thumbnail paddingWrapper">
              <div class="text-center">
                <a 
                  class="routeSchool" 
                  href="#" 
                  @click.prevent="accessSchool(school)"
                  :data-token="school.token"
                  :data-route="school.route"
                >
                  <img 
                    v-if="school.nameImage" 
                    :src="`/images/${school.nameImage}`" 
                    width="150"
                    :alt="school.name"
                  >
                  <i v-else>No tiene logo</i>
                </a>
              </div>
              <div class="caption text-center">
                <a 
                  class="routeSchool" 
                  href="#" 
                  @click.prevent="accessSchool(school)"
                  :data-token="school.token"
                  :data-route="school.route"
                >
                  <h4>{{ convertTitle(school.name) }}</h4>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div v-else>
          <h2 class="text-center">Por favor comuníquese con soporte para que le asignen una institución.</h2>
        </div>
      </div>
    </div>

    <!-- Modal para crear institución (solo Super Admin) -->
    <SchoolModal 
      v-if="showModal"
      :school="selectedSchool"
      :is-edit-mode="isEditMode"
      @close="closeModal"
      @save="handleSaveSchool"
    />
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import axios from '../../plugins/axios'
import Header from '../Layout/Header.vue'
import SchoolModal from './SchoolModal.vue'

export default {
  name: 'SchoolsIndex',
  components: {
    Header,
    SchoolModal
  },
  setup() {
    const store = useStore()
    const router = useRouter()
    
    // State local del componente
    const showModal = ref(false)
    const selectedSchool = ref(null)
    const isEditMode = ref(false)
    const isAccessing = ref(false)
    
    // Computed properties desde el store
    const isLoading = computed(() => store.getters.isLoading)
    const error = computed(() => store.getters.error)
    const schools = computed(() => store.getters.schools)
    const hasSchools = computed(() => store.getters.hasSchools)
    const canCreateAccounts = computed(() => store.getters.canCreateAccounts)
    const user = computed(() => store.getters.user)
    
    // Methods
    const convertTitle = (text) => {
      if (!text) return ''
      return text.toLowerCase().replace(/\b\w/g, l => l.toUpperCase())
    }
    
    const loadSchools = async () => {
      console.log('🏢 [COMPONENT] Iniciando loadSchools...')
      console.log('📊 [COMPONENT] Estado inicial:', {
        hasSchools: hasSchools.value,
        isLoading: isLoading.value,
        schoolsCount: schools.value.length,
        error: error.value
      })
      
      try {
        console.log('🔄 [COMPONENT] Llamando store.dispatch(auth/fetchUserSchools)...')
        const result = await store.dispatch('auth/fetchUserSchools')
        
        console.log('✅ [COMPONENT] Resultado de fetchUserSchools:', result)
        console.log('📊 [COMPONENT] Estado después de la llamada:', {
          hasSchools: hasSchools.value,
          schoolsCount: schools.value.length,
          isLoading: isLoading.value,
          error: error.value
        })
        
        if (!result.success) {
          console.error('❌ [COMPONENT] Error cargando instituciones:', result.error)
        } else {
          console.log('✅ [COMPONENT] Instituciones cargadas exitosamente')
        }
      } catch (err) {
        console.error('❌ [COMPONENT] Error inesperado cargando instituciones:', err)
      }
    }
    
    const accessSchool = async (school) => {
      if (isAccessing.value) return
      
      isAccessing.value = true
      
      try {
        console.log('Accediendo a institución:', school.name)
        
        const result = await store.dispatch('auth/accessSchool', school.token)

        if (result.success) {
          const redirectUrl = school.route
              ? `/institucion/inst/${school.route}`
              : '/institucion/inst/salon'
          // ¡faltaba esto!
          router.push(redirectUrl)
          return


        } else {
          alert(result.error || 'No tiene permisos para acceder a esta institución')
        }
      } catch (err) {
        console.error('Error al acceder a institución:', err)
        alert('Error al acceder a la institución. Por favor, inténtelo de nuevo.')
      } finally {
        isAccessing.value = false
      }
    }
    
    const openCreateModal = () => {
      selectedSchool.value = null
      isEditMode.value = false
      showModal.value = true
    }
    
    const closeModal = () => {
      showModal.value = false
      selectedSchool.value = null
      isEditMode.value = false
    }
    
    const handleSaveSchool = async (schoolData) => {
      try {
        const response = await axios.post('/api/schools', schoolData)
        
        if (response.data.success) {
          closeModal()
          await loadSchools() // Recargar la lista
          alert('Institución creada exitosamente')
        } else {
          alert('Error al crear la institución')
        }
      } catch (error) {
        console.error('Error guardando institución:', error)
        alert('Error al guardar la institución')
      }
    }
    
    // Lifecycle
    onMounted(async () => {
      console.log('🚀 [COMPONENT] onMounted ejecutado')
      console.log('📊 [COMPONENT] Estado en onMounted:', {
        hasSchools: hasSchools.value,
        isLoading: isLoading.value,
        schoolsCount: schools.value.length,
        user: user.value,
        isAuthenticated: store.getters.isAuthenticated
      })
      
      // Solo cargar si no se han cargado ya
      if (!hasSchools.value && !isLoading.value) {
        console.log('✅ [COMPONENT] Condiciones cumplidas, cargando schools...')
        await loadSchools()
      } else {
        console.log('⏭️ [COMPONENT] No se cargan schools:', {
          hasSchools: hasSchools.value,
          isLoading: isLoading.value,
          reason: hasSchools.value ? 'Ya tiene schools' : 'Está cargando'
        })
      }
    })
    
    return {
      // State
      showModal,
      selectedSchool,
      isEditMode,
      isAccessing,
      
      // Computed
      isLoading,
      error,
      schools,
      hasSchools,
      canCreateAccounts,
      user,
      
      // Methods
      convertTitle,
      loadSchools,
      accessSchool,
      openCreateModal,
      closeModal,
      handleSaveSchool
    }
  }
}
</script>

<style scoped>
.schools-container {
  min-height: 100vh;
  background-color: #f8f9fa;
}

.page {
  background: white;
  padding: 20px;
  border-bottom: 1px solid #e9ecef;
  margin-bottom: 20px;
}

.page h2 {
  margin: 0 0 15px 0;
  color: #333;
}

.list-inline-block ul {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
}

.list-inline-block li {
  margin-right: 15px;
  position: relative;
}

.list-inline-block li:not(:last-child)::after {
  content: '>';
  position: absolute;
  right: -10px;
  color: #6c757d;
}

.list-inline-block a {
  color: #007bff;
  text-decoration: none;
}

.list-inline-block .active-page a {
  color: #6c757d;
  font-weight: bold;
}

.paddingWrapper {
  padding: 20px;
}

.thumbnail {
  display: block;
  padding: 4px;
  margin-bottom: 20px;
  line-height: 1.42857143;
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 4px;
  transition: border .2s ease-in-out;
  min-height: 250px;
}

.thumbnail:hover,
.thumbnail:focus,
.thumbnail.active {
  border-color: #337ab7;
}

.thumbnail .caption {
  padding: 9px;
  color: #333;
}

.thumbnail .caption h4 {
  margin: 10px 0;
  color: #333;
}

.thumbnail a {
  text-decoration: none;
  color: inherit;
}

.thumbnail a:hover {
  text-decoration: none;
}

.routeSchool {
  cursor: pointer;
  transition: all 0.3s ease;
}

.routeSchool:hover {
  transform: scale(1.05);
}

.loading-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 60px 20px;
}

.loading-container p {
  margin-top: 15px;
  color: #6c757d;
}

.alert {
  margin: 20px;
  padding: 20px;
}

.btn {
  border-radius: 4px;
  transition: all 0.3s ease;
}

.btn:hover {
  transform: translateY(-1px);
}

.text-center {
  text-align: center;
}

@media (max-width: 768px) {
  .paddingWrapper {
    padding: 10px;
  }
  
  .col-sm-6,
  .col-md-4 {
    width: 100%;
    margin-bottom: 20px;
  }
  
  .thumbnail {
    min-height: 200px;
  }
}
</style> 