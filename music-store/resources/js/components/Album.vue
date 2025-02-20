<script setup>
    import axios from 'axios';
    import { user } from '../SharedData';

    const props = defineProps({
        album: Object,
    });

    const buy = async () => {
        try {
            const response = await axios.post(`/api/albums/${props.album.id}/purchase`, {}, {
                headers: {
                    Authorization: `Bearer ${user.token}`
                }
            });

            props.album.owned = true;
        } catch (error) {
            alert('An error occurred while purchasing the album.');
            console.error(error);
        }
    }
</script>

<template>
    <div class="flex flex-row w-full mt-4 bg-neutral-300 shadow-sm border border-neutral-400 rounded-lg">
        <div class="relative p-2.5 md:w-1/5 shrink-0 overflow-hidden">
            <img :src="'/storage/' + album.artwork" class="h-full w-full rounded-md md:rounded-lg object-cover" />
        </div>
        <div class="p-6 flex flex-col justify-center">
            <h4 class="mb-2 text-slate-800 text-xl font-semibold"> {{ album.title }} </h4>
            <h5 class="mb-2 text-slate-800 text-sm font-semibold"> Artist: {{ album.artist?.name }} </h5>
            <div class="mb-2 text-slate-800 text-sm font-semibold"> Genre: {{ album.genre }} </div>
            <div v-if="album.songs" class="mb-2 text-slate-800 text-sm font-semibold"> Songs: {{ album.songs?.length }} </div>
        </div>

        <div @click="buy" class="p-6 flex flex-col justify-center m-5 ml-auto bg-neutral-400 hover:bg-neutral-500 rounded-lg">
            <p v-if="!album.owned">Buy</p>
            <p v-else>Owned</p>
        </div>
    </div> 
</template>