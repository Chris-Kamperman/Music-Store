<script setup>
    import { RouterLink, useRouter } from 'vue-router'
    import { reactive } from 'vue';
    import axios from 'axios';
    import { user } from '../SharedData';

    const credentials = reactive({
        email: '',
        password: ''
    });

    const router = useRouter();

    const login = async () => {
        try {
            const result = await axios.post('/api/login', credentials);

            user.login(result.data.token, result.data.user);

            router.push('/');
        } catch (error) {
            if(error.response && error.response.status === 401) {
                alert('Invalid credentials.');
            } else {
                console.error(error);
            }
        }
    }
</script>

<template>
    <div class="flex min-h-[calc(100vh-4rem)] flex-col justify-center px-6 py-12 lg:px-8 items-center">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Sign into your account</h2>
        </div>
        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-6" action="#" @submit.prevent="login">
                <div>
                    <label for="email" class="block text-sm/6 font-medium text-gray-900">Email address</label>
                    <div class="mt-2">
                        <input type="email" name="email" id="email" v-model="credentials.email" autocomplete="email" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm/6 font-medium text-gray-900">Password</label>
                    </div>
                    <div class="mt-2">
                        <input type="password" name="password" id="password" v-model="credentials.password" autocomplete="current-password" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                    </div>
                </div>

                <div>
                    <button type="submit" class="flex w-full justify-center rounded-md bg-gray-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-xs hover:bg-gray-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign in</button>
                </div>
            </form>
            <div class="mt-2 text-center">
                <RouterLink to="/register" class="text-sm/6 font-medium text-blue-900 hover:text-blue-700">Not registered yet?</RouterLink>
            </div>
        </div>
    </div>
</template>