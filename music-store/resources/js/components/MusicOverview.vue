<script setup>
    import { computed, ref } from 'vue';
    import { RouterLink } from 'vue-router';

    const props = defineProps({
        albums: Object,
    });

    const search = ref('');

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
</script>
<template>
    <div class="grid grid-cols-1 gap-4 p-4 h-100%">
        <div class="bg-blue-300 w-1/1 p-4 m-4 flex flex-col rounded-lg shadow-xl">
            <h1 class="text-2xl font-bold">Music Overview</h1>
            <input v-model="search" type="text" class="w-1/1 p-4 m-4 rounded-lg shadow-xl" placeholder="Search for music...">
        </div>
        <div v-for="album in wantedAlbums" class="bg-blue-300 w-1/1 p-4 m-4 flex flex-col rounded-lg shadow-xl">
            <RouterLink :to="'/album/' + album.id" class="flex flex-row">
                <div class="grid place-items-center overflow-x-scroll rounded-lg p-6 lg:overflow-visible">
                    <img class="object-cover object-center w-full rounded-lg shadow-xl shadow-blue-gray-900/50"
                        :src="album.cover"
                        alt="Album cover" />
                </div>
                <div>
                    <h1>Title: {{ album.title }}</h1>
                    <p>Artist: {{ album.artist.name }} </p>
                    <p>Genre: {{ album.genre }}</p>
                </div>
            </RouterLink>
            <hr class="h-px my-8 bg-black border-0">
            <div v-for="song in album.songs" class="bg-blue-300 w-1/1 p-4 m-4 flex flex-row rounded-lg shadow-xl">
                <p>Title: {{ song.title }}</p>
            </div>
        </div>
    </div>

</template>