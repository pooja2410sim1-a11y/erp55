<template>
    <div class="login-wrap">
        <div class="login-card">
            <h1>Welcome back</h1>
            <p>Sign in to TextileERP to manage users, roles, and permissions.</p>

            <form @submit.prevent="handleLogin">
                <label>Email</label>
                <input v-model="email" type="email" placeholder="admin@company.com" required />

                <label>Password</label>
                <input v-model="password" type="password" placeholder="Enter password" required />

                <button type="submit">Sign In</button>

                <p v-if="error" class="error">{{ error }}</p>
            </form>
        </div>
    </div>
</template>

<script>
import axios from '../axios'

export default {
    data() {
        return {
            email: '',
            password: '',
            error: null,
        }
    },
    methods: {
        async handleLogin() {
            this.error = null
            try {
                await axios.get('/sanctum/csrf-cookie')
                await axios.post('/login', {
                    email: this.email,
                    password: this.password,
                })
                this.$router.push('/admin/dashboard')
            } catch (error) {
                this.error = 'Invalid credentials. Please try again.'
            }
        },
    },
}
</script>

<style scoped>
.login-wrap {
    min-height: 100vh;
    background: radial-gradient(circle at top left, #112147 0%, #040912 60%);
    display: grid;
    place-items: center;
    padding: 20px;
}

.login-card {
    width: 100%;
    max-width: 440px;
    border: 1px solid rgba(113, 155, 255, 0.35);
    background: #0b1328;
    border-radius: 18px;
    padding: 28px;
    color: #e7efff;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
}

h1 {
    margin: 0;
    font-size: 32px;
}

p {
    color: #93a8cf;
}

label {
    display: block;
    margin-top: 14px;
    margin-bottom: 6px;
    font-size: 14px;
    color: #c8dafb;
}

input {
    width: 100%;
    box-sizing: border-box;
    background: #111d38;
    border: 1px solid #2f4677;
    color: #e7efff;
    border-radius: 10px;
    padding: 12px;
}

button {
    width: 100%;
    margin-top: 18px;
    background: linear-gradient(135deg, #1e90ff, #2563eb);
    color: white;
    border: none;
    border-radius: 10px;
    padding: 12px;
    font-weight: 700;
}

.error {
    margin-top: 10px;
    color: #ff9d9d;
}
</style>
