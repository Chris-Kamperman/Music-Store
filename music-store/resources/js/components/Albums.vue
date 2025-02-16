<script setup>
    import { ref, onMounted } from 'vue';
    import Album from './Album.vue';
    import { user } from '../SharedData';
    import axios from 'axios';

    const albums = ref([]);

    onMounted(async () => {
        try {
            const response = await axios.get('/api/albums', {
                headers: {
                    Authorization: `Bearer ${user.token}`
                }
            });

            albums.value = response.data;
        } catch (error) {
            console.error(error);
        }
    });
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-1">
        <Album v-for="album in albums" :album="album" />
    </div>
</template>