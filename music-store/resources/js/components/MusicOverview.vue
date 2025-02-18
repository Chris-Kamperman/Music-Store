<script setup>
    import { computed, ref } from 'vue';
    import { RouterLink } from 'vue-router';
    import axios from 'axios';
    import { user } from '../SharedData';

    const props = defineProps({
        albums: Object,
        canDownload: {
            type: Boolean,
            default: false,
        }
    });

    const search = ref('');
    const sortBy = ref('id');

    const wantedAlbums = computed(() => {
        const albums = props.albums;
        const filter = search.value.toLowerCase().trim();

        return albums.filter((album) => {
            if(album.title.toLowerCase().includes(filter))
                return true;

            if(album.artist.name.toLowerCase().includes(filter))
                return true;

            if(album.genre.toLowerCase().includes(filter))
                return true;

            if(album.songs.some((song) => song.title.toLowerCase().includes(filter)))
                return true;
        });
    });

    const sortedAlbums = computed(() => {
        switch(sortBy.value) {               
            case 'title':
                return wantedAlbums.value.sort((a, b) => a.title.localeCompare(b.title));
            case 'artist':
                return wantedAlbums.value.sort((a, b) => a.artist.name.localeCompare(b.artist.name));
            case 'genre':
                return wantedAlbums.value.sort((a, b) => a.genre.localeCompare(b.genre));
            default:
                return wantedAlbums.value.sort((a, b) => a.id - b.id);
        }
    });

    const download = async (title, id) => {
        try {
            const result = await axios.get(`/api/songs/${id}/download`, {
                headers: {
                    Authorization: `Bearer ${user.token}`,
                    Accept: 'audio/mpeg',
                },
                responseType: 'blob',
            });

            const blob = new Blob([result.data], { type: 'audio/mpeg' });
            const url = window.URL.createObjectURL(blob);

            const a = document.createElement('a');
            a.href = url;
            a.download = `${title}.mp3`;
            a.click();
        } catch (error) {
            console.error(error);
        }
    };
</script>
<template>
    <div class="grid grid-cols-1 gap-4 p-4 h-100%">
        <div class="bg-blue-300 w-1/1 p-4 m-4 flex flex-col rounded-lg shadow-xl">
            <h1 class="text-2xl font-bold">Music Overview</h1>
            <input v-model="search" type="text" class="w-1/1 p-4 mx-4 my-2 rounded-lg shadow-xl" placeholder="Search for music...">
            <select v-model="sortBy" class="w-1/1 p-4 mx-4 rounded-lg shadow-xl">
                <option value="id" selected disabled>Sort by</option>
                <option value="id">Default</option>
                <option value="title">Sort by Title</option>
                <option value="artist">Sort by Artist</option>
                <option value="genre">Sort by Genre</option>
            </select>
        </div>
        <div v-for="album in sortedAlbums" class="bg-blue-300 w-1/1 p-4 m-4 flex flex-col rounded-lg shadow-xl">
            <RouterLink :to="'/album/' + album.id" class="flex flex-row">
                <div class="grid place-items-center overflow-x-scroll rounded-lg p-6 lg:overflow-visible">
                    <img class="object-cover object-center w-full rounded-lg shadow-xl shadow-blue-gray-900/50" 
                    :src="'/storage/' + album.artwork" alt="Album cover" />
                </div>
                <div>
                    <h1>Title: {{ album.title }}</h1>
                    <p>Artist: {{ album.artist.name }} </p>
                    <p>Genre: {{ album.genre }}</p>
                </div>
            </RouterLink>
            <hr class="h-px my-8 bg-black border-0">
            <div v-for="song in album.songs" class="bg-blue-300 w-1/1 p-4 m-4 flex flex-row rounded-lg shadow-xl justify-between">
                <p>Title: {{ song.title }}</p>
                <button v-if="canDownload" @click="download(song.title, song.id)" class="bg-red-300">Download</button>
            </div>
        </div>
    </div>

</template>