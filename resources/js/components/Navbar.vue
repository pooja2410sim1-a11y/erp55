<template>
    <header class="topbar">
        <div class="left-tools">
            <button class="icon-btn">☰</button>
            <button class="icon-btn">⌕</button>
        </div>

        <div class="right-tools" v-if="auth.user">
            <div class="role-pill">Role: {{ auth.roles[0] || 'User' }}</div>
            <div class="avatar">{{ (auth.user.first_name || 'U').slice(0, 1).toUpperCase() }}</div>
            <button @click="logout" class="logout-btn">Sign out</button>
        </div>
    </header>
</template>

<script>
import axios from '../axios'
import { authState } from '../auth'

export default {
    setup() {
        const logout = async () => {
            await axios.post('/logout')
            window.location.href = import.meta.env.BASE_URL + 'login'
        }

        return {
            auth: authState,
            logout,
        }
    },
}
</script>

<style scoped>
.topbar {
    height: 68px;
    border-bottom: 1px solid rgba(95, 130, 201, 0.2);
    background: #0b1328;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
}

.left-tools,
.right-tools {
    display: flex;
    align-items: center;
    gap: 12px;
}

.icon-btn {
    border: 1px solid #2a3d63;
    background: #101f3f;
    color: #9bb3e4;
    width: 36px;
    height: 36px;
    border-radius: 8px;
}

.role-pill {
    padding: 8px 14px;
    border: 1px solid #2a3d63;
    border-radius: 10px;
    color: #cde0ff;
    background: #111f3a;
    font-size: 13px;
}

.avatar {
    width: 36px;
    height: 36px;
    border-radius: 999px;
    background: linear-gradient(135deg, #1e90ff, #2563eb);
    display: grid;
    place-items: center;
    font-weight: 700;
}

.logout-btn {
    border: 1px solid #2a3d63;
    background: transparent;
    color: #d6e4ff;
    border-radius: 10px;
    padding: 8px 12px;
}
</style>
