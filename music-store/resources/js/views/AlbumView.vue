<script setup>
    import axios from 'axios';
    import { user } from '../SharedData';
    import { ref, onBeforeMount } from 'vue';
    import { useRoute } from 'vue-router';
    import Album from '../components/Album.vue';

    const route = useRoute();
    const albumId = route.params.id;
    const album = ref(null);

    onBeforeMount(async () => {
        try {
            const response = await axios.get(`/api/albums/${albumId}`, {
                headers: {
                    Authorization: `Bearer ${user.token}`
                }
            });

            album.value = response.data;
        } catch (error) {
            console.error(error);
        }
    });
</script>

<template>
    <div class="flex flex-col items-center justify-center mx-80">
        <Album :album="album" />
        <div class="relative flex flex-col md:flex-row bg-gray-300 shadow-sm border border-slate-200 rounded-lg w-1/2 justify-center">
            <div v-for="song in album.songs"> 
                {{ song.title }} 
            </div>
        </div>
    </div>
</template>