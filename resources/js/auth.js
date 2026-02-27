import { reactive } from 'vue'
import axios from './axios'

export const authState = reactive({
    user: null,
    roles: [],
    permissions: [],
    loading: true
})

export async function fetchUser() {
    try {
        const response = await axios.get('/user')

        authState.user = response.data
        authState.roles = response.data.roles || []
        authState.permissions = response.data.permissions || []
    } catch (error) {
        authState.user = null
        authState.roles = []
        authState.permissions = []
    } finally {
        authState.loading = false
    }
}

export function hasRole(role) {
    return authState.roles.includes(role)
}

export function hasPermission(permission) {
    return authState.permissions.includes(permission)
}
