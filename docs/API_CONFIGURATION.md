# Configuración de API Externa - Friendly Account

## 📋 Descripción

Este documento explica cómo configurar la conexión a una API externa en el proyecto Friendly Account. El sistema está diseñado para ser flexible y permitir conexiones a diferentes APIs según el entorno.

## 🔧 Configuración Inicial

### 1. Variables de Entorno

Crea un archivo `.env` en la raíz del proyecto basado en `.env.example`:

```bash
# Copiar el archivo de ejemplo
cp .env.example .env
```

### 2. Configurar la URL de la API

Edita el archivo `.env` y configura la URL de tu API:

```env
# URL base de la API externa
VITE_API_BASE_URL=http://localhost:8000/api

# Timeout para peticiones (opcional)
VITE_API_TIMEOUT=10000
```

### 3. Ejemplos de Configuración

#### Desarrollo Local (Laravel)
```env
VITE_API_BASE_URL=http://localhost:8000/api
```

#### Servidor de Staging
```env
VITE_API_BASE_URL=https://staging-api.tudominio.com/api
```

#### Producción
```env
VITE_API_BASE_URL=https://api.tudominio.com/api
```

## 🏗️ Arquitectura del Sistema

### Archivos de Configuración

```
src/
├── config/
│   └── api.js              # Configuración central de la API
├── services/
│   ├── httpService.js      # Servicio HTTP con axios
│   └── authService.js      # Servicio de autenticación
└── store/
    └── modules/Auth/       # Store de Vuex actualizado
```

### 1. Configuración Central (`src/config/api.js`)

```javascript
// Configuración base
export const API_CONFIG = {
  BASE_URL: import.meta.env.VITE_API_BASE_URL,
  TIMEOUT: import.meta.env.VITE_API_TIMEOUT,
  // ... más configuraciones
}

// Endpoints organizados
export const API_ENDPOINTS = {
  AUTH: {
    LOGIN: '/auth/login',
    LOGOUT: '/auth/logout',
    // ... más endpoints
  }
}
```

### 2. Servicio HTTP (`src/services/httpService.js`)

```javascript
// Uso básico
import httpService from '@/services/httpService.js'

// GET request
const response = await httpService.get('/users')

// POST request
const response = await httpService.post('/users', userData)
```

### 3. Servicio de Autenticación (`src/services/authService.js`)

```javascript
// Uso en componentes
import authService from '@/services/authService.js'

// Login
const result = await authService.login(credentials)

// Verificar autenticación
const isAuth = authService.isAuthenticated()
```

## 🔌 Uso en Componentes

### Ejemplo en un Componente Vue

```vue
<script setup>
import httpService from '@/services/httpService.js'
import { API_ENDPOINTS } from '@/config/api.js'

const fetchUsers = async () => {
  try {
    const response = await httpService.get(API_ENDPOINTS.USERS.LIST)
    
    if (response.success) {
      users.value = response.data
    } else {
      console.error('Error:', response.error.message)
    }
  } catch (error) {
    console.error('Error inesperado:', error)
  }
}
</script>
```

### Ejemplo con Vuex Store

```javascript
// En las acciones del store
import httpService from '@/services/httpService.js'

export default {
  async fetchData({ commit }) {
    const response = await httpService.get('/data')
    
    if (response.success) {
      commit('SET_DATA', response.data)
      return response
    } else {
      commit('SET_ERROR', response.error.message)
      return response
    }
  }
}
```

## 🔐 Autenticación

### Flujo de Autenticación

1. **Login**: El usuario envía credenciales
2. **Token**: La API devuelve un token JWT
3. **Almacenamiento**: El token se guarda en localStorage
4. **Interceptor**: Todas las peticiones incluyen el token automáticamente
5. **Renovación**: El token se renueva automáticamente si es necesario

### Manejo de Tokens

```javascript
// El servicio maneja automáticamente:
// - Agregar token a headers
// - Renovar tokens expirados
// - Limpiar tokens inválidos
// - Redirigir al login si es necesario
```

## 📡 Endpoints Disponibles

### Autenticación
- `POST /auth/login` - Iniciar sesión
- `POST /auth/logout` - Cerrar sesión
- `GET /auth/me` - Obtener usuario actual
- `POST /auth/refresh` - Renovar token

### Escuelas
- `GET /schools` - Listar escuelas
- `POST /schools` - Crear escuela
- `GET /schools/:id` - Obtener escuela
- `PUT /schools/:id` - Actualizar escuela

### Usuarios
- `GET /users` - Listar usuarios
- `POST /users` - Crear usuario
- `GET /users/:id` - Obtener usuario
- `PUT /users/:id` - Actualizar usuario

## 🛠️ Personalización

### Agregar Nuevos Endpoints

1. **Actualizar configuración**:
```javascript
// src/config/api.js
export const API_ENDPOINTS = {
  // ... endpoints existentes
  STUDENTS: {
    LIST: '/students',
    CREATE: '/students',
    SHOW: '/students/:id'
  }
}
```

2. **Crear servicio específico**:
```javascript
// src/services/studentService.js
import httpService from './httpService.js'
import { API_ENDPOINTS } from '@/config/api.js'

class StudentService {
  async getStudents() {
    return await httpService.get(API_ENDPOINTS.STUDENTS.LIST)
  }
  
  async createStudent(data) {
    return await httpService.post(API_ENDPOINTS.STUDENTS.CREATE, data)
  }
}

export default new StudentService()
```

### Configurar Headers Personalizados

```javascript
// src/config/api.js
export const API_CONFIG = {
  DEFAULT_HEADERS: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-Custom-Header': 'valor-personalizado'
  }
}
```

## 🐛 Debugging

### Logs en Desarrollo

El sistema incluye logs automáticos en modo desarrollo:

```javascript
// Aparecen en la consola del navegador
🚀 API Request: GET /users
✅ API Response: 200 /users
❌ API Error: 401 /auth/login
```

### Verificar Configuración

```javascript
// En la consola del navegador
console.log('API Base URL:', import.meta.env.VITE_API_BASE_URL)
console.log('API Timeout:', import.meta.env.VITE_API_TIMEOUT)
```

## 🔒 Seguridad

### Buenas Prácticas

1. **Variables de Entorno**: Nunca hardcodear URLs en el código
2. **Tokens**: Se almacenan de forma segura en localStorage
3. **HTTPS**: Usar siempre HTTPS en producción
4. **CORS**: Configurar correctamente en el backend
5. **Validación**: Validar todas las respuestas de la API

### Manejo de Errores

```javascript
// El sistema maneja automáticamente:
// - Errores de red (sin conexión)
// - Errores de autenticación (401)
// - Errores del servidor (500+)
// - Timeouts de peticiones
```

## 📚 Recursos Adicionales

- [Documentación de Axios](https://axios-http.com/)
- [Variables de Entorno en Vite](https://vitejs.dev/guide/env-and-mode.html)
- [Vuex Store Modules](https://vuex.vuejs.org/guide/modules.html)

## 🆘 Solución de Problemas

### Error: "Network Error"
- Verificar que la API esté ejecutándose
- Comprobar la URL en `.env`
- Verificar configuración de CORS

### Error: "401 Unauthorized"
- Token expirado o inválido
- Verificar credenciales de login
- Comprobar configuración de autenticación

### Error: "Timeout"
- Aumentar `VITE_API_TIMEOUT`
- Verificar velocidad de conexión
- Optimizar consultas en el backend 