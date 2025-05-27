import { createRouter, createWebHistory } from 'vue-router'
import store from '../store'
import { requireAuth, requireGuest, loadSchools } from './middleware'

// Importar vistas de forma lazy loading para mejor performance
const HomeView = () => import('../views/HomeView.vue')
const AboutView = () => import('../views/AboutView.vue')
const LoginView = () => import('../views/LoginView.vue')
const SchoolsView = () => import('../views/SchoolsView.vue')

// Definir las rutas
const routes = [
    {
        path: '/',
        name: 'home',
        component: HomeView,
        meta: { 
            title: 'Inicio'
        }
    },
    {
        path: '/login',
        name: 'login',
        component: LoginView,
        beforeEnter: requireGuest,
        meta: { 
            title: 'Iniciar Sesión'
        }
    },
    {
        path: '/about',
        name: 'about',
        component: AboutView,
        meta: { 
            title: 'Acerca de'
        }
    },
    {
        path: '/auth/login',
        redirect: '/login'
    },
    {
        path: '/schools',
        name: 'schools',
        component: SchoolsView,
        beforeEnter: [requireAuth, loadSchools],
        meta: { 
            title: 'Instituciones'
        }
    },
    {
        path: '/app',
        redirect: '/schools'
    },
    {
        // Capturar rutas no encontradas
        path: '/:pathMatch(.*)*',
        redirect: '/'
    }
]

// Crear el router
const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        // Siempre scroll al top en navegación
        return { top: 0 }
    }
})

// Guard global mejorado
router.beforeEach(async (to, from, next) => {
    console.log('🧭 [ROUTER] Navegando de', from.path, 'a', to.path)
    
    // Solo proceder si el beforeEnter no maneja la autenticación
    if (!to.beforeEnter || !Array.isArray(to.beforeEnter)) {
        next()
        return
    }
    
    next()
})

// Actualizar título de la página
router.afterEach((to) => {
    const defaultTitle = 'Friendly Account'
    document.title = to.meta.title ? `${to.meta.title} - ${defaultTitle}` : defaultTitle
    
    console.log('📄 [ROUTER] Navegación completada a:', to.path, '- Título:', document.title)
})

export default router 