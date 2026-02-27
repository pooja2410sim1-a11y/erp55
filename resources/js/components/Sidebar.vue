<template>
    <aside class="sidebar">
        <div class="brand">TextileERP</div>

        <nav>
            <p class="section">Main</p>
            <router-link v-if="hasPermission('dashboard.view')" to="/admin/dashboard" class="item" active-class="active">Dashboard</router-link>
            <router-link v-if="hasPermission('user.view')" to="/admin/users" class="item" active-class="active">Users</router-link>
            <router-link v-if="hasPermission('role.view')" to="/admin/roles" class="item" active-class="active">Roles</router-link>
            <router-link v-if="hasPermission('manage_permissions')" to="/admin/permissions" class="item" active-class="active">Permissions</router-link>

            <p class="section">Modules</p>
            <span class="item disabled">Buyer Orders</span>
            <span class="item disabled">Bill of Materials</span>
            <span class="item disabled">Purchase Orders</span>
        </nav>
    </aside>
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
