<script setup>
import { computed, onMounted, ref } from 'vue'
import axios from '../../axios'
import { hasPermission } from '../../auth'

const roles = ref([])
const showRoleModal = ref(false)
const editRoleId = ref(null)
const roleForm = ref({ name: '', label: '' })

const canView = computed(() => hasPermission('role.view'))
const canAdd = computed(() => hasPermission('role.add'))
const canEdit = computed(() => hasPermission('role.edit'))
const canDelete = computed(() => hasPermission('role.delete'))

const loadRoles = async () => {
    const response = await axios.get('/api/roles')
    roles.value = response.data
}

onMounted(async () => {
    if (canView.value) await loadRoles()
})

const openModal = (role = null) => {
    if (role) {
        editRoleId.value = role.id
        roleForm.value = { name: role.name, label: role.label }
    } else {
        editRoleId.value = null
        roleForm.value = { name: '', label: '' }
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
    await loadRoles()
}

const deleteRole = async (id) => {
    if (!confirm('Delete this role?')) return
    await axios.delete(`/api/roles/${id}`)
    await loadRoles()
}
</script>

<template>
    <section class="page-card">
        <div class="header-row">
            <div>
                <h1>Role Management</h1>
                <p>Roles determine user access levels across the ERP system.</p>
            </div>
            <button v-if="canAdd" class="primary" @click="openModal()">+ Add Role</button>
        </div>

        <table v-if="canView">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Role Name</th>
                    <th>Label</th>
                    <th>Users</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(role, index) in roles" :key="role.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ role.name }}</td>
                    <td>{{ role.label }}</td>
                    <td>{{ role.users_count || 0 }}</td>
                    <td>
                        <button v-if="canEdit" @click="openModal(role)">Edit</button>
                        <button v-if="canDelete" class="danger" @click="deleteRole(role.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <div v-if="showRoleModal" class="modal-overlay">
        <div class="modal">
            <h3>{{ editRoleId ? 'Edit Role' : 'Add New Role' }}</h3>
            <p class="sub">Roles determine user access levels across the system.</p>

            <label>Role Name *</label>
            <input v-model="roleForm.name" placeholder="e.g., WarehouseManager" />

            <label>Role Label *</label>
            <input v-model="roleForm.label" placeholder="e.g., Warehouse Manager" />

            <div class="actions">
                <button @click="showRoleModal = false">Cancel</button>
                <button class="primary" @click="saveRole">{{ editRoleId ? 'Update' : 'Add' }}</button>
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
.modal { width:min(520px,92vw); background:#111a33; border:1px solid #2f4677; border-radius:14px; padding:18px; color:#e8f1ff; }
.sub { color:#8da5d2; margin-top:2px; }
label { display:block; margin-top:10px; margin-bottom:6px; color:#a6bce6; }
input { width:100%; box-sizing:border-box; padding:11px; background:#0d1630; border:1px solid #314a7d; color:#e8f1ff; border-radius:10px; }
.actions { display:flex; justify-content:flex-end; gap:8px; margin-top:14px; }
</style>
