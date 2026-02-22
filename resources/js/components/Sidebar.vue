<template>
    <div class="sidebar">
        <h2 class="logo">ERP</h2>

        <ul>
            <!-- Dashboard -->
            <li v-if="hasRole('SuperAdmin')">
                <router-link to="/" active-class="active-link">
                    Dashboard
                </router-link>
            </li>

            <!-- User Management -->
            <li v-if="hasRole('SuperAdmin')">
                <router-link to="/admin/users" active-class="active-link">
                    User Management
                </router-link>
            </li>

            <!-- Placeholder -->
            <li>
                <router-link to="#">
                    Future Module
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script>
import { hasRole } from '../auth'

export default {
    setup() {
        return { hasRole }
    }
}
</script>

<style scoped>
.sidebar {
    width: 220px;
    background: #1e293b;
    color: white;
    padding: 20px;
}

.logo {
    margin-bottom: 30px;
}

ul {
    list-style: none;
    padding: 0;
}

li {
    margin-bottom: 15px;
}

a {
    color: white;
    text-decoration: none;
    display: block;
    padding: 6px 10px;
    border-radius: 6px;
}

a:hover {
    background: #334155;
}

.active-link {
    background: #0ea5e9;
}
</style>
