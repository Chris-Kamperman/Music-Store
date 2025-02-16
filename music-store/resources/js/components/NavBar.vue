<script setup>
    import { RouterLink, useRouter } from 'vue-router';
    import { user } from '../SharedData';

    const router = useRouter();

    const logout = () => {
        user.logout();
        router.push('/login');
    };
</script>

<template>
    <nav class="bg-white border-gray-200 dark:bg-gray-900">
        <div class="max-w-screen-xl min-w-screen-xl flex flex-wrap items-center justify-between p-4">
            <RouterLink to="/" class="text-lg font-bold text-gray-800 dark:text-gray-200">Music Store</RouterLink>
            <div v-if="user.isLoggedIn()" class="flex items-center space-x-4">
                <RouterLink to="/albums" class="text-gray-800 dark:text-gray-200">Albums</RouterLink>
                <RouterLink to="/artists" class="text-gray-800 dark:text-gray-200">Artists</RouterLink>
            </div>
            <div class="max-w-screen-xl flex flex-wrap items-end justify-between p-4">
                <RouterLink v-if="user.isLoggedIn()" to="/login" class="text-gray-800 dark:text-gray-200" @click="logout">Logout</RouterLink>
            </div>
        </div>
    </nav>
</template>