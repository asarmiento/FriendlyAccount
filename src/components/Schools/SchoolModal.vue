<template>
  <div class="modal-overlay" @click="closeModal">
    <div class="modal-dialog" @click.stop>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            {{ isEditMode ? 'Editar Institución' : 'Nueva Institución' }}
          </h5>
          <button type="button" class="btn-close" @click="closeModal">
            <i class="fa fa-times"></i>
          </button>
        </div>
        
        <form @submit.prevent="handleSubmit">
          <div class="modal-body">
            <!-- Mostrar errores -->
            <div v-if="errors.length > 0" class="alert alert-danger">
              <ul class="mb-0">
                <li v-for="error in errors" :key="error">{{ error }}</li>
              </ul>
            </div>

            <div class="row">
              <!-- Nombre -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="name" class="form-label">
                    Nombre <span class="text-danger">*</span>
                  </label>
                  <input
                    id="name"
                    type="text"
                    class="form-control"
                    v-model="form.name"
                    :class="{ 'is-invalid': fieldErrors.name }"
                    required
                  >
                  <div v-if="fieldErrors.name" class="invalid-feedback">
                    {{ fieldErrors.name }}
                  </div>
                </div>
              </div>

              <!-- Cédula -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="charter" class="form-label">
                    Cédula <span class="text-danger">*</span>
                  </label>
                  <input
                    id="charter"
                    type="text"
                    class="form-control"
                    v-model="form.charter"
                    :class="{ 'is-invalid': fieldErrors.charter }"
                    required
                  >
                  <div v-if="fieldErrors.charter" class="invalid-feedback">
                    {{ fieldErrors.charter }}
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <!-- Route -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="route" class="form-label">Route</label>
                  <input
                    id="route"
                    type="text"
                    class="form-control"
                    v-model="form.route"
                    :class="{ 'is-invalid': fieldErrors.route }"
                  >
                  <div v-if="fieldErrors.route" class="invalid-feedback">
                    {{ fieldErrors.route }}
                  </div>
                </div>
              </div>

              <!-- Ciudad -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="town" class="form-label">
                    Ciudad <span class="text-danger">*</span>
                  </label>
                  <input
                    id="town"
                    type="text"
                    class="form-control"
                    v-model="form.town"
                    :class="{ 'is-invalid': fieldErrors.town }"
                    required
                  >
                  <div v-if="fieldErrors.town" class="invalid-feedback">
                    {{ fieldErrors.town }}
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <!-- Teléfono 1 -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="phoneOne" class="form-label">
                    Teléfono 1 <span class="text-danger">*</span>
                  </label>
                  <input
                    id="phoneOne"
                    type="tel"
                    class="form-control"
                    v-model="form.phoneOne"
                    :class="{ 'is-invalid': fieldErrors.phoneOne }"
                    required
                  >
                  <div v-if="fieldErrors.phoneOne" class="invalid-feedback">
                    {{ fieldErrors.phoneOne }}
                  </div>
                </div>
              </div>

              <!-- Teléfono 2 -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="phoneTwo" class="form-label">Teléfono 2</label>
                  <input
                    id="phoneTwo"
                    type="tel"
                    class="form-control"
                    v-model="form.phoneTwo"
                    :class="{ 'is-invalid': fieldErrors.phoneTwo }"
                  >
                  <div v-if="fieldErrors.phoneTwo" class="invalid-feedback">
                    {{ fieldErrors.phoneTwo }}
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <!-- Fax -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="fax" class="form-label">Fax</label>
                  <input
                    id="fax"
                    type="tel"
                    class="form-control"
                    v-model="form.fax"
                    :class="{ 'is-invalid': fieldErrors.fax }"
                  >
                  <div v-if="fieldErrors.fax" class="invalid-feedback">
                    {{ fieldErrors.fax }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Dirección -->
            <div class="form-group">
              <label for="address" class="form-label">
                Dirección <span class="text-danger">*</span>
              </label>
              <textarea
                id="address"
                class="form-control"
                v-model="form.address"
                :class="{ 'is-invalid': fieldErrors.address }"
                rows="3"
                required
              ></textarea>
              <div v-if="fieldErrors.address" class="invalid-feedback">
                {{ fieldErrors.address }}
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeModal">
              Cancelar
            </button>
            <button type="submit" class="btn btn-primary" :disabled="isLoading">
              <span v-if="isLoading" class="spinner-border spinner-border-sm me-2"></span>
              {{ isLoading ? 'Guardando...' : (isEditMode ? 'Actualizar' : 'Crear') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive, watch, onMounted } from 'vue'

export default {
  name: 'SchoolModal',
  props: {
    school: {
      type: Object,
      default: null
    },
    isEditMode: {
      type: Boolean,
      default: false
    }
  },
  emits: ['close', 'save'],
  setup(props, { emit }) {
    const form = reactive({
      name: '',
      charter: '',
      route: '',
      town: '',
      phoneOne: '',
      phoneTwo: '',
      fax: '',
      address: ''
    })
    
    const errors = ref([])
    const fieldErrors = ref({})
    const isLoading = ref(false)
    
    // Llenar formulario si es modo edición
    const populateForm = () => {
      if (props.school && props.isEditMode) {
        Object.keys(form).forEach(key => {
          form[key] = props.school[key] || ''
        })
      } else {
        // Limpiar formulario para modo creación
        Object.keys(form).forEach(key => {
          form[key] = ''
        })
      }
    }
    
    // Validación del formulario
    const validateForm = () => {
      const newErrors = {}
      errors.value = []
      
      if (!form.name.trim()) {
        newErrors.name = 'El nombre es requerido'
      }
      
      if (!form.charter.trim()) {
        newErrors.charter = 'La cédula es requerida'
      }
      
      if (!form.town.trim()) {
        newErrors.town = 'La ciudad es requerida'
      }
      
      if (!form.phoneOne.trim()) {
        newErrors.phoneOne = 'El teléfono 1 es requerido'
      } else if (!/^[\d\-\+\(\)\s]+$/.test(form.phoneOne)) {
        newErrors.phoneOne = 'El teléfono debe tener un formato válido'
      }
      
      if (!form.address.trim()) {
        newErrors.address = 'La dirección es requerida'
      }
      
      // Validaciones adicionales para teléfonos opcionales
      if (form.phoneTwo && !/^[\d\-\+\(\)\s]+$/.test(form.phoneTwo)) {
        newErrors.phoneTwo = 'El teléfono debe tener un formato válido'
      }
      
      if (form.fax && !/^[\d\-\+\(\)\s]+$/.test(form.fax)) {
        newErrors.fax = 'El fax debe tener un formato válido'
      }
      
      fieldErrors.value = newErrors
      return Object.keys(newErrors).length === 0
    }
    
    // Enviar formulario
    const handleSubmit = async () => {
      if (!validateForm()) {
        errors.value = Object.values(fieldErrors.value)
        return
      }
      
      isLoading.value = true
      errors.value = []
      fieldErrors.value = {}
      
      try {
        const formData = { ...form }
        if (props.isEditMode && props.school) {
          formData.id = props.school.id
        }
        
        emit('save', formData)
      } catch (error) {
        console.error('Error en el formulario:', error)
        errors.value = ['Error inesperado. Por favor, intenta de nuevo.']
      } finally {
        isLoading.value = false
      }
    }
    
    // Cerrar modal
    const closeModal = () => {
      emit('close')
    }
    
    // Manejar tecla Escape
    const handleKeyDown = (event) => {
      if (event.key === 'Escape') {
        closeModal()
      }
    }
    
    // Watchers
    watch(() => props.school, populateForm, { immediate: true })
    watch(() => props.isEditMode, populateForm)
    
    // Lifecycle
    onMounted(() => {
      populateForm()
      document.addEventListener('keydown', handleKeyDown)
    })
    
    return {
      form,
      errors,
      fieldErrors,
      isLoading,
      handleSubmit,
      closeModal
    }
  }
}
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1050;
  padding: 20px;
}

.modal-dialog {
  width: 100%;
  max-width: 800px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-content {
  background: white;
  border-radius: 8px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  overflow: hidden;
}

.modal-header {
  background: #f8f9fa;
  padding: 20px;
  border-bottom: 1px solid #e9ecef;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.modal-title {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 600;
  color: #333;
}

.btn-close {
  background: none;
  border: none;
  font-size: 1.2rem;
  color: #6c757d;
  cursor: pointer;
  padding: 5px;
  border-radius: 4px;
  transition: all 0.3s ease;
}

.btn-close:hover {
  background: #e9ecef;
  color: #333;
}

.modal-body {
  padding: 30px;
  max-height: 60vh;
  overflow-y: auto;
}

.modal-footer {
  background: #f8f9fa;
  padding: 20px;
  border-top: 1px solid #e9ecef;
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

.form-group {
  margin-bottom: 20px;
}

.form-label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  color: #333;
}

.form-control {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #ced4da;
  border-radius: 4px;
  font-size: 1rem;
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-control:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.form-control.is-invalid {
  border-color: #dc3545;
}

.invalid-feedback {
  display: block;
  color: #dc3545;
  font-size: 0.875rem;
  margin-top: 5px;
}

.alert {
  padding: 15px;
  border-radius: 4px;
  margin-bottom: 20px;
}

.alert-danger {
  background-color: #f8d7da;
  border: 1px solid #f5c6cb;
  color: #721c24;
}

.alert ul {
  margin: 0;
  padding-left: 20px;
}

.text-danger {
  color: #dc3545 !important;
}

.btn {
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-primary {
  background: #007bff;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background: #0056b3;
  transform: translateY(-1px);
}

.btn-primary:disabled {
  background: #6c757d;
  cursor: not-allowed;
}

.btn-secondary {
  background: #6c757d;
  color: white;
}

.btn-secondary:hover {
  background: #545b62;
  transform: translateY(-1px);
}

.spinner-border-sm {
  width: 1rem;
  height: 1rem;
  border-width: 0.125em;
}

.me-2 {
  margin-right: 0.5rem;
}

.row {
  display: flex;
  flex-wrap: wrap;
  margin: 0 -10px;
}

.col-md-6 {
  flex: 0 0 50%;
  max-width: 50%;
  padding: 0 10px;
}

@media (max-width: 768px) {
  .col-md-6 {
    flex: 0 0 100%;
    max-width: 100%;
  }
  
  .modal-dialog {
    max-width: 95vw;
    margin: 10px;
  }
  
  .modal-body {
    padding: 20px;
  }
}
</style> 