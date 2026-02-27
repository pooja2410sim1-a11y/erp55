<template>
    <aside class="sidebar">
        <div class="brand">TextileERP</div>

        <ul>
            <li v-if="hasRole('SuperAdmin')">
                <router-link to="/admin/dashboard" active-class="active-link">
                    Dashboard
                </router-link>
            </li>

            <li v-if="hasRole('SuperAdmin')">
                <router-link to="/admin/users" active-class="active-link">
                    User Management
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script>
import { hasPermission } from '../auth'

export default {
    setup() {
        return { hasPermission }
    },
}
</script>

<style scoped>
.sidebar {
    width: 280px;
    background: #070d1b;
    border-right: 1px solid rgba(95, 130, 201, 0.2);
    padding: 18px 14px;
}

.brand {
    color: #eaf2ff;
    font-size: 30px;
    font-weight: 800;
    margin: 6px 8px 16px;
}

.section {
    color: #6d82ad;
    font-size: 12px;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    margin: 14px 10px 8px;
}

.item {
    display: block;
    color: #bbcef6;
    padding: 11px 12px;
    text-decoration: none;
    border-radius: 10px;
    margin-bottom: 4px;
}

.item:hover {
    background: #101c36;
}

.active {
    background: #142a52;
    color: #4da3ff;
    font-weight: 600;
}

.disabled {
    opacity: 0.5;
}
</style>
