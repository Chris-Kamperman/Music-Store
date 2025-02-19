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
        <div class="overflow-x-auto shadow-md rounded-lg mt-5 w-full border border-neutral-400">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase bg-neutral-400 sticky top-0">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Song title
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Artist
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Genre
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Duration
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="song in album.songs" :key="song.id" class="bg-neutral-300 border-b border-gray-200">
                        <th scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                            {{ song.title }}
                        </th>
                        <td class="px-6 py-4">
                            {{ album.artist.name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ album.genre }}
                        </td>
                        <td class="px-6 py-4">
                            {{ song.duration }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>