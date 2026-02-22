import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { initializeAuth } from './auth'

const app = createApp(App)

app.use(router)

await initializeAuth()

app.mount('#app')
