import { createRouter, createWebHistory } from 'vue-router'
import MainLayout from '../layouts/MainLayout.vue'
import Home from '../pages/Home.vue'
import Login from '../pages/Login.vue'
import UserManagement from '../pages/admin/UserManagement.vue'
import RoleManagement from '../pages/admin/RoleManagement.vue'
import PermissionManagement from '../pages/admin/PermissionManagement.vue'
import { authState, hasPermission } from '../auth'

const routes = [
    {
        path: '/',
        component: MainLayout,
        meta: { requiresAuth: true },
        children: [
            { path: '', redirect: '/admin/dashboard' },
            {
                path: '',
                redirect: '/admin/dashboard'
            },
            {
                path: 'admin/dashboard',
                name: 'Home',
                component: Home,
                meta: { permission: 'dashboard.view' },
            },
            {
                path: 'admin/users',
                name: 'UserManagement',
                component: UserManagement,
                meta: { permission: 'user.view' },
            },
            {
                path: 'admin/roles',
                name: 'RoleManagement',
                component: RoleManagement,
                meta: { permission: 'role.view' },
            },
            {
                path: 'admin/permissions',
                name: 'PermissionManagement',
                component: PermissionManagement,
                meta: { permission: 'manage_permissions' },
            },
        ],
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
    },
]

const router = createRouter({
    history: createWebHistory('/'),
    routes,
})

router.beforeEach((to, from, next) => {
    if (authState.loading) return next()
    if (authState.loading) {
        return next()
    }

    if (to.meta.requiresAuth && !authState.user) {
        return next('/login')
    }

    if (to.meta.permission && !hasPermission(to.meta.permission)) {
        return next('/login')
    }

    if (to.path === '/login' && authState.user) {
        return next('/admin/dashboard')
    }

    next()
})

export default router
