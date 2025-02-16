<script setup>
    import axios from 'axios';
    import { user } from '../SharedData';

    const props = defineProps({
        album: Object,
        allowPurchase: {
            type: Boolean,
            default: false
        }
    });

    const buy = async () => {
        console.log("oi")
        try {
            const response = await axios.post(`/api/albums/${props.album.id}/purchase`, {}, {
                headers: {
                    Authorization: `Bearer ${user.token}`
                }
            });

            console.log(response.data);
        } catch (error) {
            console.error(error);
        }
    }
</script>

<template>
    <RouterLink :to="'/album/' + album.id" class="relative flex flex-col md:flex-row w-full mt-4 bg-gray-300 shadow-sm border border-slate-200 rounded-lg">
        <div class="flex flex-col md:flex-row w-full">
            <div class="relative p-2.5 md:w-1/5 shrink-0 overflow-hidden">
                <img
                :src="album.artwork"

                class="h-full w-full rounded-md md:rounded-lg object-cover"
                />
            </div>
            <div class="p-6 flex flex-col justify-center">
                <h4 class="mb-2 text-slate-800 text-xl font-semibold"> {{ album.title }} </h4>
                <h5 class="mb-2 text-slate-800 text-sm font-semibold"> Artist: {{ album.artist.name }} </h5>
                <div class="mb-2 text-slate-800 text-sm font-semibold"> Genre: {{ album.genre }} </div>
                <div v-if="album.songs" class="mb-2 text-slate-800 text-sm font-semibold"> Songs: {{ album.songs.length }} </div>
            </div>

            <div v-if="allowPurchase" @click="buy" class="p-6 flex flex-col justify-center ml-auto bg-gray-400">
                Buy
            </div>
        </div> 
    </RouterLink>
</template>