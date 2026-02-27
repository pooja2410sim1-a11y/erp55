<script setup>
import { computed, onMounted, ref } from 'vue'
import axios from '../../axios'

const roles = ref([])
const permissions = ref([])
const selectedRoleId = ref('')

const selectedRole = computed(() => roles.value.find((role) => role.id === selectedRoleId.value))

const groupedModules = computed(() => {
    const actionList = ['view', 'add', 'edit', 'approve', 'delete']
    const map = {}

    permissions.value.forEach((permission) => {
        const parts = permission.name.split('.')
        if (parts.length !== 2) return
        const [module, action] = parts
        if (!actionList.includes(action)) return

        if (!map[module]) {
            map[module] = {
                module,
                label: module.charAt(0).toUpperCase() + module.slice(1),
                permissions: {},
            }
        }
        map[module].permissions[action] = permission
    })

    return Object.values(map)
})

const loadData = async () => {
    const response = await axios.get('/api/permissions')
    permissions.value = response.data.permissions
    roles.value = response.data.roles
    if (!selectedRoleId.value && roles.value.length) {
        selectedRoleId.value = roles.value[0].id
    }
}

onMounted(loadData)

const hasRolePermission = (permissionId) => {
    if (!selectedRole.value) return false
    return selectedRole.value.permissions.some((permission) => permission.id === permissionId)
}

const togglePermission = async (permissionId, checked) => {
    if (!selectedRole.value) return

    let ids = selectedRole.value.permissions.map((permission) => permission.id)

    if (checked) {
        ids.push(permissionId)
    } else {
        ids = ids.filter((id) => id !== permissionId)
    }

    await axios.post(`/api/roles/${selectedRole.value.id}/permissions`, {
        permissions: [...new Set(ids)],
    })

    await loadData()
}
</script>

<template>
    <section class="page-card">
        <div class="header-row">
            <div>
                <h1>Permission Management</h1>
                <p>Configure module-wise View/Add/Edit/Approve/Delete permissions by role.</p>
            </div>
            <select v-model="selectedRoleId" class="role-select">
                <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.label }}</option>
            </select>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Module</th>
                    <th>View</th>
                    <th>Add</th>
                    <th>Edit</th>
                    <th>Approve</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in groupedModules" :key="row.module">
                    <td>{{ row.label }}</td>

                    <td v-for="action in ['view', 'add', 'edit', 'approve', 'delete']" :key="`${row.module}-${action}`">
                        <input
                            v-if="row.permissions[action]"
                            type="checkbox"
                            :checked="hasRolePermission(row.permissions[action].id)"
                            @change="togglePermission(row.permissions[action].id, $event.target.checked)"
                        />
                        <span v-else>-</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>
</template>

<style scoped>
.page-card { background:#0b1328; border:1px solid #263b62; border-radius:14px; padding:18px; }
.header-row { display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; gap:12px; }
h1 { margin:0; color:#f3f7ff; }
p { margin:6px 0 0; color:#93a8cf; }
.role-select { min-width:240px; padding:10px; border-radius:10px; border:1px solid #2f4677; background:#111d38; color:#dbe8ff; }

table { width:100%; border-collapse:collapse; color:#d4e4ff; }
th,td { border-bottom:1px solid #1f3256; padding:12px; text-align:left; }
th { color:#89a2d2; }
input[type='checkbox'] { width:20px; height:20px; accent-color:#1e90ff; }
</style>
