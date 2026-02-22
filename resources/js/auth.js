import { reactive } from 'vue'
import axios from './axios'

export const authState = reactive({
    user: null,
    roles: [],
    loading: true
})

let authBootstrapPromise = null

export async function fetchUser() {
    try {
        const response = await axios.get('/user')

        authState.user = response.data
        authState.roles = response.data.roles || []

    } catch (error) {
        authState.user = null
        authState.roles = []
    } finally {
        authState.loading = false
    }
}

export function initializeAuth() {
    if (!authBootstrapPromise) {
        authBootstrapPromise = fetchUser()
    }

    return authBootstrapPromise
}

export function hasRole(role) {
    return authState.roles.includes(role)
}
