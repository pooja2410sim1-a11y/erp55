<template>
    <div style="background:#1f2937; color:white; padding:12px 20px; display:flex; justify-content:space-between; align-items:center;">
        <div>
            <strong>Kaushal ERP</strong>
        </div>

        <div v-if="auth.user">
            Welcome, {{ auth.user.first_name }}
            |
            <button 
                @click="logout"
                style="background:none; border:none; color:white; cursor:pointer; margin-left:5px;">
                Logout
            </button>
        </div>
    </div>
</template>

<script>
import axios from '../axios'
import { authState } from '../auth'

export default {
    setup() {

        function logout() {
            axios.post('/logout').then(() => {
                window.location.href = import.meta.env.BASE_URL + 'login'
            })
        }

        return {
            auth: authState,
            logout
        }
    }
}
</script>