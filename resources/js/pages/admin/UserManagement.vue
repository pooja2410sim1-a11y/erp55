<script setup>
import { computed, onMounted, ref } from 'vue'
import axios from '../../axios'
import { hasPermission } from '../../auth'

const users = ref([])
const roles = ref([])
const showUserModal = ref(false)
const editUserId = ref(null)

const userForm = ref({
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    role_id: '',
    permission_ids: [],
})

const canViewUsers = computed(() => hasPermission('user.view'))
const canAddUsers = computed(() => hasPermission('user.add'))
const canEditUsers = computed(() => hasPermission('user.edit'))
const canDeleteUsers = computed(() => hasPermission('user.delete'))

const loadUsers = async () => {
    const response = await axios.get('/api/users')
    users.value = response.data
}

const loadRoles = async () => {
    const response = await axios.get('/api/roles')
    roles.value = response.data
}

onMounted(async () => {
    if (canViewUsers.value) await loadUsers()
    if (hasPermission('role.view')) await loadRoles()
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
            permission_ids: user.permissions?.map((p) => p.id) ?? [],
        }
    } else {
        editUserId.value = null
        userForm.value = { first_name: '', last_name: '', email: '', password: '', role_id: '', permission_ids: [] }
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
    await loadUsers()
}

const deleteUser = async (id) => {
    if (!confirm('Delete this user?')) return
    await axios.delete(`/api/users/${id}`)
    await loadUsers()
}
</script>

<template>
    <section class="page-card">
        <div class="header-row">
            <div>
                <h1>User Management</h1>
                <p>Manage users with first name and second name fields separately.</p>
            </div>
            <button v-if="canAddUsers" class="primary" @click="openUserModal()">+ Add User</button>
        </div>

        <table v-if="canViewUsers">
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
                        <button v-if="canEditUsers" @click="openUserModal(user)">Edit</button>
                        <button v-if="canDeleteUsers" class="danger" @click="deleteUser(user.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <div v-if="showUserModal" class="modal-overlay">
        <div class="modal">
            <h3>{{ editUserId ? 'Edit User' : 'Add New User' }}</h3>
            <p class="sub">The user will receive access based on assigned role and permissions.</p>

            <label>First Name *</label>
            <input v-model="userForm.first_name" placeholder="e.g., Ravi" />

            <label>Second Name *</label>
            <input v-model="userForm.last_name" placeholder="e.g., Kumar" />

            <label>Email (Login) *</label>
            <input v-model="userForm.email" placeholder="e.g., ravi@textileerp.com" />

            <label>{{ editUserId ? 'Password (optional)' : 'Password *' }}</label>
            <input v-model="userForm.password" type="password" placeholder="Min 6 characters" />

            <label>Role *</label>
            <select v-model="userForm.role_id">
                <option disabled value="">Select role</option>
                <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.label }}</option>
            </select>

            <div class="actions">
                <button @click="showUserModal = false">Cancel</button>
                <button class="primary" @click="saveUser">{{ editUserId ? 'Update User' : 'Create User' }}</button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.page-card { background:#0b1328; border:1px solid #263b62; border-radius:14px; padding:18px; }
.header-row { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; }
h1 { margin:0; color:#f3f7ff; }
p { margin:6px 0 0; color:#93a8cf; }
.primary { background:#1e90ff; border:none; color:white; padding:10px 14px; border-radius:10px; }
button { border:1px solid #2f4677; background:#111d38; color:#d8e7ff; padding:8px 10px; border-radius:8px; margin-right:6px; }
.danger { border-color:#7b2f3b; background:#3a1520; }

table { width:100%; border-collapse:collapse; color:#d4e4ff; }
th,td { border-bottom:1px solid #1f3256; padding:12px; text-align:left; }
th { color:#89a2d2; }

.modal-overlay { position:fixed; inset:0; background:rgba(2,5,12,0.75); display:grid; place-items:center; }
.modal { width:min(560px,92vw); background:#111a33; border:1px solid #2f4677; border-radius:14px; padding:18px; color:#e8f1ff; }
.sub { color:#8da5d2; margin-top:2px; }
label { display:block; margin-top:10px; margin-bottom:6px; color:#a6bce6; }
input,select { width:100%; box-sizing:border-box; padding:11px; background:#0d1630; border:1px solid #314a7d; color:#e8f1ff; border-radius:10px; }
.actions { display:flex; justify-content:flex-end; gap:8px; margin-top:14px; }
</style>
