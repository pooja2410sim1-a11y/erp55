<script setup>
import { ref, onMounted } from 'vue'
import axios from '../../axios'

const activeTab = ref('users')

const users = ref([])
const roles = ref([])
const permissions = ref([])

const showUserModal = ref(false)
const showRoleModal = ref(false)

const editUserId = ref(null)
const editRoleId = ref(null)

/* -----------------------
   USER FORM
----------------------- */

const userForm = ref({
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    role_id: ''
})

/* -----------------------
   ROLE FORM
----------------------- */

const roleForm = ref({
    name: '',
    label: ''
})

/* -----------------------
   LOAD DATA
----------------------- */

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

onMounted(() => {
    loadUsers()
    loadRoles()
})

/* -----------------------
   USER CRUD
----------------------- */

const openUserModal = (user = null) => {
    if (user) {
        editUserId.value = user.id
        userForm.value = {
            first_name: user.first_name,
            last_name: user.last_name,
            email: user.email,
            password: '',
            role_id: user.roles.length ? user.roles[0].id : ''
        }
    } else {
        editUserId.value = null
        userForm.value = {
            first_name: '',
            last_name: '',
            email: '',
            password: '',
            role_id: ''
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
    loadUsers()
}

const deleteUser = async (id) => {
    if (confirm('Delete this user?')) {
        await axios.delete(`/api/users/${id}`)
        loadUsers()
    }
}

/* -----------------------
   ROLE CRUD
----------------------- */

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
    loadRoles()
}

const deleteRole = async (id) => {
    if (confirm('Delete this role?')) {
        await axios.delete(`/api/roles/${id}`)
        loadRoles()
    }
}

/* -----------------------
   PERMISSION TOGGLE
----------------------- */

const togglePermission = async (roleId, permissionId, checked) => {
    const role = roles.value.find(r => r.id === roleId)

    let updatedPermissions = role.permissions.map(p => p.id)

    if (checked) {
        updatedPermissions.push(permissionId)
    } else {
        updatedPermissions = updatedPermissions.filter(id => id !== permissionId)
    }

    await axios.post(`/api/roles/${roleId}/permissions`, {
        permissions: updatedPermissions
    })

    loadPermissions()
}
</script>

<template>
<div class="admin-container">

    <h2>User Management</h2>

    <!-- Tabs -->
    <div class="tabs">
        <button :class="{active: activeTab==='users'}" @click="activeTab='users'">Users</button>
        <button :class="{active: activeTab==='roles'}" @click="activeTab='roles'">Roles</button>
        <button :class="{active: activeTab==='permissions'}" @click="activeTab='permissions'; loadPermissions()">Permissions</button>
    </div>

    <!-- USERS TAB -->
    <div v-if="activeTab==='users'">
        <button class="primary" @click="openUserModal()">Add User</button>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="u in users" :key="u.id">
                    <td>{{ u.first_name }} {{ u.last_name }}</td>
                    <td>{{ u.email }}</td>
                    <td>{{ u.roles.length ? u.roles[0].label : '' }}</td>
                    <td>
                        <button @click="openUserModal(u)">Edit</button>
                        <button @click="deleteUser(u.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- ROLES TAB -->
    <div v-if="activeTab==='roles'">
        <button class="primary" @click="openRoleModal()">Add Role</button>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Label</th>
                    <th>Users</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="r in roles" :key="r.id">
                    <td>{{ r.name }}</td>
                    <td>{{ r.label }}</td>
                    <td>{{ r.users_count }}</td>
                    <td>
                        <button @click="openRoleModal(r)">Edit</button>
                        <button @click="deleteRole(r.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- PERMISSIONS TAB -->
    <div v-if="activeTab==='permissions'">

        <table>
            <thead>
                <tr>
                    <th>Permission</th>
                    <th v-for="r in roles" :key="r.id">{{ r.label }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="p in permissions" :key="p.id">
                    <td>{{ p.label }}</td>
                    <td v-for="r in roles" :key="r.id">
                        <input type="checkbox"
                               :checked="r.permissions.some(x => x.id === p.id)"
                               @change="togglePermission(r.id, p.id, $event.target.checked)" />
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

    <!-- USER MODAL -->
    <div v-if="showUserModal" class="modal">
        <div class="modal-box">
            <h3>{{ editUserId ? 'Edit User' : 'Add User' }}</h3>

            <input v-model="userForm.first_name" placeholder="First Name" />
            <input v-model="userForm.last_name" placeholder="Last Name" />
            <input v-model="userForm.email" placeholder="Email" />
            <input v-model="userForm.password" type="password" placeholder="Password" />

            <select v-model="userForm.role_id">
                <option value="">Select Role</option>
                <option v-for="r in roles" :key="r.id" :value="r.id">
                    {{ r.label }}
                </option>
            </select>

            <button class="primary" @click="saveUser()">Save</button>
            <button @click="showUserModal=false">Cancel</button>
        </div>
    </div>

    <!-- ROLE MODAL -->
    <div v-if="showRoleModal" class="modal">
        <div class="modal-box">
            <h3>{{ editRoleId ? 'Edit Role' : 'Add Role' }}</h3>

            <input v-model="roleForm.name" placeholder="System Name" />
            <input v-model="roleForm.label" placeholder="Display Label" />

            <button class="primary" @click="saveRole()">Save</button>
            <button @click="showRoleModal=false">Cancel</button>
        </div>
    </div>

</div>
</template>

<style scoped>
.admin-container {
    padding: 20px;
    color: #fff;
}

.tabs button {
    margin-right: 10px;
    padding: 8px 15px;
    background: #333;
    border: none;
    color: #fff;
    cursor: pointer;
}

.tabs button.active {
    background: #007bff;
}

.primary {
    background: #007bff;
    color: white;
    border: none;
    padding: 8px 15px;
    margin-bottom: 10px;
    cursor: pointer;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

table th, table td {
    border: 1px solid #444;
    padding: 8px;
}

.modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.7);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-box {
    background: #222;
    padding: 20px;
    width: 400px;
    display: flex;
    flex-direction: column;
}

.modal-box input,
.modal-box select {
    margin-bottom: 10px;
    padding: 8px;
    background: #333;
    border: 1px solid #555;
    color: white;
}
</style>