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
        alert('An error occurred while fetching the albums data.');
        console.error(error);
    }
});
</script>

<template>
<MusicOverview :albums="ownedAlbums" title='Owned Music' :canDownload=true></MusicOverview>

</template>