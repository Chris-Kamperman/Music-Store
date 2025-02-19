<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { user } from '../SharedData';
import MusicOverview from '../components/MusicOverview.vue';

const ownedAlbums = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get('/api/user/albums', {
            headers: {
                Authorization: `Bearer ${user.token}`,
                Accept: 'application/json',
            },
        });

        ownedAlbums.value = response.data;
    } catch (error) {
        console.error(error);
    }
});
</script>

<template>
<MusicOverview class="w-100% h-100%" :albums="ownedAlbums" :canDownload="true"></MusicOverview>

</template>