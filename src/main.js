import './assets/main.css'
import './assets/styles/global.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'

const app = createApp(App)

app.use(store)
app.use(router)


// Inicializar la aplicación
store.dispatch('initializeApp').then(() => {
  app.mount('#app')
})
