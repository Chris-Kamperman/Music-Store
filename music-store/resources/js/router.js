import { createRouter, createWebHistory } from 'vue-router';
import { user } from './SharedData';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component: () => import('./views/HomeView.vue'),
            meta: { requiresAuth: true, requiresGuest: false }
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('./views/LoginView.vue'),
            meta: { requiresAuth: false, requiresGuest: true }
        },
        {
            path: '/register',
            name: 'register',
            component: () => import('./views/RegisterView.vue'),
            meta: { requiresAuth: false, requiresGuest: true }
        },


    ]
});

router.beforeEach((to, from) => {
    if(to.meta.requiresAuth && !user.isLoggedIn()) {
        return '/login';
    }

    return true;
});

export default router;