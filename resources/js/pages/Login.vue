<template>
    <div style="max-width:400px;">
        <h2>Login</h2>

        <form @submit.prevent="handleLogin">
            <div style="margin-bottom:10px;">
                <input v-model="email" type="email" placeholder="Email" required />
            </div>

            <div style="margin-bottom:10px;">
                <input v-model="password" type="password" placeholder="Password" required />
            </div>

            <button type="submit">Login</button>

            <p v-if="error" style="color:red;">
                {{ error }}
            </p>
        </form>
    </div>
</template>

<script>
import axios from '../axios'

export default {
    data() {
        return {
            email: '',
            password: '',
            error: null
        }
    },
    methods: {
    async handleLogin() {
        this.error = null

        try {
            await axios.get('/sanctum/csrf-cookie')

            await axios.post('/login', {
                email: this.email,
                password: this.password
            })

            this.$router.push('/')

        } catch (err) {
            this.error = 'Invalid credentials'
        }
    }
}
}
</script>
