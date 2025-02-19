<script setup>
    import axios from 'axios';
    import { user } from '../SharedData';
    import { ref, onMounted } from 'vue';

    import MusicOverview from '../components/MusicOverview.vue';

    const albums = ref([]);

    onMounted(async () => {
        try {
            const response = await axios.get('/api/albums', {
                headers: {
                    Authorization: `Bearer ${user.token}`,
                    Accept: 'application/json',
                },
            });

            albums.value = response.data;
        } catch (error) {
            console.error(error);
        }
    });
</script>

<template>
    <MusicOverview :albums="albums" title="Music Overview"/>
</template>