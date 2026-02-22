import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { fetchUser } from './auth'

const app = createApp(App)

app.use(router)

app.mount('#app')

// Fetch user AFTER mount
fetchUser()