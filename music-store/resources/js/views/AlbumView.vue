<script setup>
    import axios from 'axios';
    import { user } from '../SharedData';
    import { ref, onBeforeMount } from 'vue';
    import { useRoute } from 'vue-router';
    import Album from '../components/Album.vue';

    const route = useRoute();
    const albumId = route.params.id;
    const album = ref({});

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
        <div class="relative bg-gray-300 shadow-sm border border-slate-200 rounded-lg w-1/2">
            <table class="table-auto w-full">  
                <thead>    
                    <tr>      
                        <th class="w-1/2 text-center">Song</th>      
                        <th class="w-1/2 text-center">Artist</th>      
                    </tr>  
                </thead>  
                <tbody>    
                    <tr v-for="song in album.songs" :key="song.id">      
                        <td class="text-center">{{ song.title }}</td>      
                        <td class="text-center">{{ album.artist?.name }}</td>      
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>