import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { fetchUser } from './auth'

async function bootstrap() {
    // Resolve auth state BEFORE first route navigation,
    // so unauthenticated users are correctly redirected to /login.
    await fetchUser()

    const app = createApp(App)
    app.use(router)
    app.mount('#app')
}

bootstrap()
