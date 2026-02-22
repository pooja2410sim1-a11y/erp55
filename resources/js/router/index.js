import { createRouter, createWebHistory } from 'vue-router'
import MainLayout from '../layouts/MainLayout.vue'
import Home from '../pages/Home.vue'
import Login from '../pages/Login.vue'
import UserManagement from '../pages/admin/UserManagement.vue'
import { authState, hasRole } from '../auth'

const routes = [
    {
        path: '/',
        component: MainLayout,
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'Home',
                component: Home,
                meta: { role: 'SuperAdmin' }
            },
            {
                path: 'admin/users',
                name: 'UserManagement',
                component: UserManagement,
                meta: { role: 'SuperAdmin' }
            }
        ]
    },
    {
        path: '/login',
        name: 'Login',
        component: Login
    }
]

const router = createRouter({
    history: createWebHistory('/'),
    routes
})

router.beforeEach((to, from, next) => {

    if (to.meta.requiresAuth && !authState.user) {
        return next('/login')
    }

    if (to.meta.role && !hasRole(to.meta.role)) {
        return next('/login')
    }

    if (to.path === '/login' && authState.user) {
        return next('/')
    }

    next()
})

export default router