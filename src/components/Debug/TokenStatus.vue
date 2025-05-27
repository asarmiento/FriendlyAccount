<template>
    <div v-if="isDevelopment" class="token-status">
        <div class="token-status-header" @click="toggleExpanded">
            🔐 Token Status {{ isExpanded ? '▼' : '▶' }}
        </div>
        
        <div v-if="isExpanded" class="token-status-content">
            <div class="status-item">
                <strong>Autenticado:</strong> 
                <span :class="isAuthenticated ? 'status-success' : 'status-error'">
                    {{ isAuthenticated ? 'Sí' : 'No' }}
                </span>
            </div>
            
            <div class="status-item">
                <strong>Token:</strong> 
                <span class="token-preview">{{ tokenPreview }}</span>
            </div>
            
            <div class="status-item">
                <strong>Usuario:</strong> 
                <span>{{ userInfo }}</span>
            </div>
            
            <div class="status-item">
                <strong>Schools:</strong> 
                <span>{{ schoolsCount }} instituciones</span>
            </div>
            
            <div class="status-actions">
                <button @click="refreshToken" :disabled="!isAuthenticated">
                    🔄 Verificar Token
                </button>
                <button @click="clearToken">
                    🗑️ Limpiar Token
                </button>
                <button @click="showTokenDetails">
                    📋 Ver Detalles
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { getAuthToken } from '../../plugins/axios'

export default {
    name: 'TokenStatus',
    
    data() {
        return {
            isExpanded: false,
            isDevelopment: process.env.NODE_ENV === 'development'
        }
    },
    
    computed: {
        ...mapGetters(['isAuthenticated', 'user', 'token', 'schools']),
        
        tokenPreview() {
            if (!this.token) return 'No token'
            return this.token.substring(0, 20) + '...'
        },
        
        userInfo() {
            if (!this.user) return 'No usuario'
            return `${this.user.name} ${this.user.last} (${this.user.email})`
        },
        
        schoolsCount() {
            return this.schools ? this.schools.length : 0
        }
    },
    
    methods: {
        toggleExpanded() {
            this.isExpanded = !this.isExpanded
        },
        
        async refreshToken() {
            try {
                const result = await this.$store.dispatch('checkAuth')
                if (result.success) {
                    this.$emit('notification', {
                        type: 'success',
                        message: 'Token verificado correctamente'
                    })
                } else {
                    this.$emit('notification', {
                        type: 'error', 
                        message: 'Token inválido'
                    })
                }
            } catch (error) {
                this.$emit('notification', {
                    type: 'error',
                    message: 'Error verificando token: ' + error.message
                })
            }
        },
        
        clearToken() {
            if (confirm('¿Estás seguro de que quieres limpiar el token? Esto cerrará tu sesión.')) {
                this.$store.commit('LOGOUT')
                this.$emit('notification', {
                    type: 'info',
                    message: 'Token limpiado, sesión cerrada'
                })
            }
        },
        
        showTokenDetails() {
            const details = {
                token: this.token,
                localStorage: getAuthToken(),
                axiosHeaders: window.axios?.defaults?.headers?.common,
                userAgent: navigator.userAgent,
                timestamp: new Date().toISOString()
            }
            
            console.log('🔐 [DEBUG] Token Details:', details)
            
            // Copiar al clipboard si está disponible
            if (navigator.clipboard) {
                navigator.clipboard.writeText(JSON.stringify(details, null, 2))
                this.$emit('notification', {
                    type: 'success',
                    message: 'Detalles copiados al clipboard y mostrados en consola'
                })
            } else {
                this.$emit('notification', {
                    type: 'info',
                    message: 'Detalles mostrados en consola'
                })
            }
        }
    }
}
</script>

<style scoped>
.token-status {
    position: fixed;
    top: 10px;
    right: 10px;
    background: #f8f9fa;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    font-family: 'Courier New', monospace;
    font-size: 12px;
    z-index: 9999;
    max-width: 300px;
}

.token-status-header {
    padding: 8px 12px;
    background: #e9ecef;
    cursor: pointer;
    user-select: none;
    font-weight: bold;
}

.token-status-header:hover {
    background: #d6dbdf;
}

.token-status-content {
    padding: 12px;
}

.status-item {
    margin-bottom: 8px;
    line-height: 1.4;
}

.status-success {
    color: #28a745;
    font-weight: bold;
}

.status-error {
    color: #dc3545;
    font-weight: bold;
}

.token-preview {
    font-family: 'Courier New', monospace;
    background: #f1f3f4;
    padding: 2px 4px;
    border-radius: 3px;
}

.status-actions {
    margin-top: 12px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.status-actions button {
    padding: 4px 8px;
    font-size: 11px;
    border: 1px solid #ccc;
    background: white;
    cursor: pointer;
    border-radius: 3px;
}

.status-actions button:hover:not(:disabled) {
    background: #f8f9fa;
}

.status-actions button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style> 