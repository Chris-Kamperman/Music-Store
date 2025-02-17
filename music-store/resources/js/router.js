import { createRouter, createWebHistory } from 'vue-router';
import { user } from './SharedData';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component: () => import('./views/HomeView.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('./views/LoginView.vue'),
            meta: { requiresAuth: false }
        },
        {
            path: '/register',
            name: 'register',
            component: () => import('./views/RegisterView.vue'),
            meta: { requiresAuth: false }
        },
        {
            path: '/album/:id',
            name: 'album',
            component: () => import('./views/AlbumView.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/owned-albums',
            name: 'owned-albums',
            component: () => import('./views/OwnedAlbumsView.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/admin',
            name: 'admin',
            component: () => import('./views/AdminView.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/:pathMatch(.*)*',
            component: () => import('./views/NotFoundView.vue')
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