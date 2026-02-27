<script setup>
import { computed, onMounted, ref } from 'vue'
import axios from '../../axios'

const activeTab = ref('users')

const users = ref([])
const roles = ref([])
const permissions = ref([])
const stats = ref({
    total_users: 0,
    admins: 0,
    users_without_role: 0,
    users_with_direct_permissions: 0
})

const showUserModal = ref(false)
const showRoleModal = ref(false)

const editUserId = ref(null)
const editRoleId = ref(null)

const userForm = ref({
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    role_id: '',
    permission_ids: []
})

const roleForm = ref({
    name: '',
    label: ''
})

const dashboardTiles = computed(() => [
    { title: 'Total Users', value: stats.value.total_users, type: 'primary' },
    { title: 'Super Admins', value: stats.value.admins, type: 'success' },
    { title: 'Without Role', value: stats.value.users_without_role, type: 'warning' },
    { title: 'Direct Permissions', value: stats.value.users_with_direct_permissions, type: 'purple' }
])

const loadUsers = async () => {
    const res = await axios.get('/api/users')
    users.value = res.data
}

const loadRoles = async () => {
    const res = await axios.get('/api/roles')
    roles.value = res.data
}

const loadPermissions = async () => {
    const res = await axios.get('/api/permissions')
    permissions.value = res.data.permissions
    roles.value = res.data.roles
}

const loadStats = async () => {
    const res = await axios.get('/api/users/stats')
    stats.value = res.data
}

const refreshDashboard = async () => {
    await Promise.all([loadUsers(), loadRoles(), loadPermissions(), loadStats()])
}

onMounted(async () => {
    await refreshDashboard()
})

const openUserModal = (user = null) => {
    if (user) {
        editUserId.value = user.id
        userForm.value = {
            first_name: user.first_name,
            last_name: user.last_name,
            email: user.email,
            password: '',
            role_id: user.roles.length ? user.roles[0].id : '',
            permission_ids: user.permissions?.map((permission) => permission.id) ?? []
        }
    } else {
        editUserId.value = null
        userForm.value = {
            first_name: '',
            last_name: '',
            email: '',
            password: '',
            role_id: '',
            permission_ids: []
        }
    }

    showUserModal.value = true
}

const saveUser = async () => {
    if (editUserId.value) {
        await axios.put(`/api/users/${editUserId.value}`, userForm.value)
    } else {
        await axios.post('/api/users', userForm.value)
    }

    showUserModal.value = false
    await refreshDashboard()
}

const deleteUser = async (id) => {
    if (confirm('Delete this user?')) {
        await axios.delete(`/api/users/${id}`)
        await refreshDashboard()
    }
}

const openRoleModal = (role = null) => {
    if (role) {
        editRoleId.value = role.id
        roleForm.value = {
            name: role.name,
            label: role.label
        }
    } else {
        editRoleId.value = null
        roleForm.value = {
            name: '',
            label: ''
        }
    }

    showRoleModal.value = true
}

const saveRole = async () => {
    if (editRoleId.value) {
        await axios.put(`/api/roles/${editRoleId.value}`, roleForm.value)
    } else {
        await axios.post('/api/roles', roleForm.value)
    }

    showRoleModal.value = false
    await refreshDashboard()
}

const deleteRole = async (id) => {
    if (confirm('Delete this role?')) {
        await axios.delete(`/api/roles/${id}`)
        await refreshDashboard()
    }
}

const togglePermission = async (roleId, permissionId, checked) => {
    const role = roles.value.find((entry) => entry.id === roleId)
    let updatedPermissions = role.permissions.map((permission) => permission.id)

    if (checked) {
        updatedPermissions.push(permissionId)
    } else {
        updatedPermissions = updatedPermissions.filter((id) => id !== permissionId)
    }

    await axios.post(`/api/roles/${roleId}/permissions`, {
        permissions: [...new Set(updatedPermissions)]
    })

    await loadPermissions()
}
</script>

<template>
    <div class="admin-page">
        <div class="hero">
            <h1>Admin Dashboard</h1>
            <p>Manage users, roles, and permissions from one place.</p>
        </div>

        <div class="tiles">
            <article v-for="tile in dashboardTiles" :key="tile.title" class="tile" :class="`tile-${tile.type}`">
                <p>{{ tile.title }}</p>
                <h3>{{ tile.value }}</h3>
            </article>
        </div>

        <div class="tabs">
            <button :class="{ active: activeTab === 'users' }" @click="activeTab = 'users'">Users</button>
            <button :class="{ active: activeTab === 'roles' }" @click="activeTab = 'roles'">Roles</button>
            <button :class="{ active: activeTab === 'permissions' }" @click="activeTab = 'permissions'">Role Permissions</button>
        </div>

        <section v-if="activeTab === 'users'" class="panel">
            <div class="panel-header">
                <h2>User List</h2>
                <button class="primary" @click="openUserModal()">Add User</button>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Direct Permissions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td>{{ user.first_name }} {{ user.last_name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.roles.length ? user.roles[0].label : '-' }}</td>
                        <td>{{ user.permissions?.length || 0 }}</td>
                        <td>
                            <button @click="openUserModal(user)">Edit</button>
                            <button class="danger" @click="deleteUser(user.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section v-if="activeTab === 'roles'" class="panel">
            <div class="panel-header">
                <h2>Role List</h2>
                <button class="primary" @click="openRoleModal()">Add Role</button>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Label</th>
                        <th>Users</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="role in roles" :key="role.id">
                        <td>{{ role.name }}</td>
                        <td>{{ role.label }}</td>
                        <td>{{ role.users_count || 0 }}</td>
                        <td>
                            <button @click="openRoleModal(role)">Edit</button>
                            <button class="danger" @click="deleteRole(role.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section v-if="activeTab === 'permissions'" class="panel">
            <h2>Permission Matrix</h2>
            <table>
                <thead>
                    <tr>
                        <th>Role</th>
                        <th v-for="permission in permissions" :key="permission.id">{{ permission.label }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="role in roles" :key="role.id">
                        <td>{{ role.label }}</td>
                        <td v-for="permission in permissions" :key="permission.id">
                            <input
                                type="checkbox"
                                :checked="role.permissions.some((entry) => entry.id === permission.id)"
                                @change="togglePermission(role.id, permission.id, $event.target.checked)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <div v-if="showUserModal" class="modal-overlay">
            <div class="modal">
                <h3>{{ editUserId ? 'Edit User' : 'Add User' }}</h3>
                <input v-model="userForm.first_name" placeholder="First name" />
                <input v-model="userForm.last_name" placeholder="Last name" />
                <input v-model="userForm.email" placeholder="Email" />
                <input v-model="userForm.password" :placeholder="editUserId ? 'Password (optional)' : 'Password'" type="password" />

                <select v-model="userForm.role_id">
                    <option disabled value="">Select Role</option>
                    <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.label }}</option>
                </select>

                <label for="direct-permissions">Direct permissions</label>
                <select id="direct-permissions" v-model="userForm.permission_ids" multiple>
                    <option v-for="permission in permissions" :key="permission.id" :value="permission.id">{{ permission.label }}</option>
                </select>

                <small>Hold Ctrl/Cmd to select multiple permissions.</small>

                <div class="actions">
                    <button class="primary" @click="saveUser">Save</button>
                    <button @click="showUserModal = false">Cancel</button>
                </div>
            </div>
        </div>

        <div v-if="showRoleModal" class="modal-overlay">
            <div class="modal">
                <h3>{{ editRoleId ? 'Edit Role' : 'Add Role' }}</h3>
                <input v-model="roleForm.name" placeholder="Role slug (example: PurchaseManager)" />
                <input v-model="roleForm.label" placeholder="Role label" />

                <div class="actions">
                    <button class="primary" @click="saveRole">Save</button>
                    <button @click="showRoleModal = false">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.admin-page { padding: 20px; }
.hero h1 { margin-bottom: 4px; }
.hero p { color: #64748b; margin-top: 0; }
.tiles { display:grid; grid-template-columns:repeat(4,minmax(160px,1fr)); gap:14px; margin:18px 0; }
.tile { border-radius:14px; padding:14px; color:#fff; box-shadow:0 8px 20px rgba(0,0,0,.08); }
.tile p { margin:0; opacity:.9; }
.tile h3 { margin:8px 0 0; font-size:28px; }
.tile-primary { background:linear-gradient(135deg,#3b82f6,#2563eb); }
.tile-success { background:linear-gradient(135deg,#10b981,#059669); }
.tile-warning { background:linear-gradient(135deg,#f59e0b,#d97706); }
.tile-purple { background:linear-gradient(135deg,#8b5cf6,#7c3aed); }

.tabs { display:flex; gap:10px; margin-bottom: 16px; }
.tabs button { padding:8px 12px; border:1px solid #cbd5e1; border-radius:10px; background:white; cursor:pointer; }
.tabs .active { background:#0f172a; color:#fff; border-color:#0f172a; }

.panel { background:#fff; border-radius:14px; padding:16px; box-shadow:0 6px 24px rgba(15,23,42,.06); }
.panel-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; }

.primary { background:#2563eb; color:#fff; border:none; padding:8px 12px; border-radius:8px; cursor:pointer; }
.danger { background:#ef4444; color:#fff; border:none; padding:6px 10px; border-radius:8px; cursor:pointer; }
button { border:1px solid #cbd5e1; background:#fff; border-radius:8px; padding:6px 10px; cursor:pointer; }

input, select { width:100%; margin-bottom:10px; padding:9px; border:1px solid #cbd5e1; border-radius:8px; }
select[multiple] { min-height: 140px; }

table { width:100%; border-collapse:collapse; }
th, td { border-bottom:1px solid #e2e8f0; padding:10px; text-align:left; font-size:14px; }

.modal-overlay { position:fixed; inset:0; background:rgba(15,23,42,.5); display:flex; align-items:center; justify-content:center; z-index:50; }
.modal { width:560px; background:#fff; border-radius:12px; padding:16px; }
.actions { display:flex; justify-content:flex-end; gap:8px; margin-top:8px; }
</style>
