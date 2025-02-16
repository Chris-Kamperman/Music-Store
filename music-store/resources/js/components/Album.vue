<script setup>
    const props = defineProps(['album'])
</script>

<template>
    <RouterLink :to="'/album/' + album.id" class="relative flex flex-col md:flex-row w-full mt-4 bg-gray-300 shadow-sm border border-slate-200 rounded-lg">
        <div class="flex flex-col md:flex-row w-full">
            <div class="relative p-2.5 md:w-1/5 shrink-0 overflow-hidden">
                <img
                src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=crop&amp;w=1471&amp;q=80"
                alt="card-image"
                class="h-full w-full rounded-md md:rounded-lg object-cover"
                />
            </div>
            <div class="p-6">
                <h4 class="mb-2 text-slate-800 text-xl font-semibold"> {{ album.title }} </h4>
                <h5 class="mb-2 text-slate-800 text-sm font-semibold"> Artist: {{ album.artist.name }} </h5>
                <div class="mb-4 rounded-full bg-teal-900 py-0.5 px-2.5 border border-transparent text-xs text-white transition-all shadow-sm w-20 text-center"> {{ album.genre }} </div>
            </div>
        </div> 
    </RouterLink>
</template>