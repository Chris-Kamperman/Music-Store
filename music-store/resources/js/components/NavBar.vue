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
        <div class="w-1/1 flex flex-wrap items-center p-4">
            <RouterLink to="/" class="text-lg font-bold text-gray-800 dark:text-gray-200">Music Store</RouterLink>
            <div v-if="user.isLoggedIn()" class="max-w-screen-xl flex flex-wrap items-end ml-auto p-4">
                <RouterLink to="/owned-albums" class="text-gray-800 dark:text-gray-200 mx-5">Owned Music</RouterLink>
                <RouterLink v-if="user.isAdmin()" to="/admin" class="text-gray-800 dark:text-gray-200 mx-5">Admin</RouterLink>
                <RouterLink to="/login" class="text-gray-800 dark:text-gray-200 mx-5" @click="logout">Logout</RouterLink>
            </div>
        </div>
    </nav>
</template>