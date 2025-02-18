<script setup>
    import axios from 'axios';
    import { ref, onMounted } from 'vue';
    import { user } from '../SharedData';

    const artists = ref([]);
    const albums = ref();
    const songs = ref([]);

    const newArtist = ref('');
    const newSong = ref({'title': '', 'album_id': null, 'file': null});
    const newAlbum = ref({'title': '', 'artist': null, 'genre': '', 'artwork': null});

    const addArtist = async () => {
        if(newArtist.value.trim() === '') {
            return;
        }

        try {
            const result = await axios.post('/api/artists', {
                name: newArtist.value.trim()
            }, {
                headers: {
                    Authorization: `Bearer ${user.token}`,
                    Accept: 'application/json'
                }
            });

            artists.value.push(result.data);
        } catch (error) {
            console.error(error);
        }
    };

    const addAlbum = async () => {
        try {
            const formData = new FormData();
            formData.append('title', newAlbum.value.title);
            formData.append('artist_id', newAlbum.value.artist);
            formData.append('genre', newAlbum.value.genre);
            formData.append('artwork', newAlbum.value.artwork);

            const headers = { 
                headers: { 
                    Authorization: `Bearer ${user.token}`,
                    Accept: 'application/json',
                    'Content-Type': 'multipart/form-data'
                } 
            };

            const result = await axios.post('/api/albums', formData, headers);

            albums.value.push(result.data);
        } catch (error) {
            console.error(error);
        }
    };

    const addSong = async () => {
        try {
            const formData = new FormData();
            formData.append('title', newSong.value.title);
            formData.append('album_id', newSong.value.album_id);
            formData.append('file', newSong.value.file);

            const headers = { 
                headers: { 
                    Authorization: `Bearer ${user.token}`,
                    Accept: 'application/json',
                    'Content-Type': 'multipart/form-data'
                } 
            };

            const result = await axios.post('/api/songs', formData, headers);
            songs.value.push(result.data);
        } catch (error) {
            console.error(error);
        }
    };

    onMounted(async () => {
        const headers = { 
            headers: { 
                Authorization: `Bearer ${user.token}`,
                Accept: 'application/json'
            } 
        };

        try {
            const artistsResult = await axios.get('/api/artists', headers);
            const songsResult = await axios.get('/api/songs', headers);
            const albumsResult = await axios.get('/api/albums', headers);

            artists.value = artistsResult.data;
            songs.value = songsResult.data;
            console.log(songsResult.data);
            albums.value = albumsResult.data;
        } catch (error) {
            console.error(error);
        }
    });
</script>
<template>
    <div class="flex flex-col items-center mx-2">
        <!-- Artists -->
        <div class="w-full">
            <div>
                <h1 class="text-2xl font-bold">Add a new artist</h1>
                <form @submit.prevent="addArtist" action="#" class="flex items-center py-2">
                    <input v-model="newArtist" type="text" required class="border border-slate-400 rounded-lg h-8" placeholder="Name" />
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg h-8 flex items-center justify-center">Add</button>
                </form>
            </div>
            <table class="table-auto w-full border border-slate-400 rounded-lg">  
                <thead>    
                    <tr>      
                        <th class="w-1/2 text-center">Id</th>      
                        <th class="w-1/2 text-center">Artist</th>      
                    </tr>  
                </thead>  
                <tbody>    
                    <tr v-for="artist in artists" :key="artist.id">      
                        <td class="text-center">{{ artist.id }}</td>      
                        <td class="text-center">{{ artist.name }}</td>      
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Albums -->
        <div class="w-full">
            <div>
                <h1 class="text-2xl font-bold">Add a new album</h1>
                <form @submit.prevent="addAlbum" action="#" class="flex items-center py-2">
                    <input v-model="newAlbum.title" type="text" required class="border border-slate-400 rounded-lg h-8" placeholder="Title" />
                    <select v-model="newAlbum.artist" required class="border border-slate-400 rounded-lg h-8">
                        <option disabled selected value="">Select an artist</option>
                        <option v-for="artist in artists" :key="artist.id" :value="artist.id">{{ artist.name }}</option>
                    </select>
                    <input v-model="newAlbum.genre" type="text" required class="border border-slate-400 rounded-lg h-8" placeholder="Genre" />
                    <input @change="newAlbum.artwork = $event.target.files[0]" type="file" accept="image/png, image/jpeg" required class="border border-slate-400 rounded-lg h-8" placeholder="Artwork" />
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg h-8 flex items-center justify-center">Add</button>
                </form>
            </div>
            <table class="table-auto w-full border border-slate-400 rounded-lg">  
                <thead>    
                    <tr>      
                        <th class="w-1/4 text-center">Id</th>
                        <th class="w-1/4 text-center">Title</th>
                        <th class="w-1/4 text-center">Artist Id</th>
                        <th class="w-1/4 text-center">Genre</th>
                    </tr>  
                </thead>  
                <tbody>    
                    <tr v-for="album in albums" :key="album.id">      
                        <td class="text-center">{{ album.id }}</td>      
                        <td class="text-center">{{ album.title }}</td>
                        <td class="text-center">{{ album.artist.id }}</td>
                        <td class="text-center">{{ album.genre }}</td>
                    </tr>
                </tbody>
            </table>
        </div> 

        <!-- Songs -->
        <div class="w-full">
            <div>
                <h1 class="text-2xl font-bold">Add a new song</h1>
                <form @submit.prevent="addSong" action="#" class="flex items-center py-2">
                    <input v-model="newSong.title" type="text" required class="border border-slate-400 rounded-lg h-8" placeholder="Title" />
                    <select v-model="newSong.album_id" required class="border border-slate-400 rounded-lg h-8">
                        <option disabled selected value="">Select an album</option>
                        <option v-for="album in albums" :key="album.id" :value="album.id">{{ album.title }}</option>
                    </select>
                    <input @change="newSong.file = $event.target.files[0]" type="file" accept="audio/mp3" required class="border border-slate-400 rounded-lg h-8" placeholder="Artwork" />
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg h-8 flex items-center justify-center">Add</button>
                </form>
            </div>
            <table class="table-auto w-full border border-slate-400 rounded-lg">  
                <thead>    
                    <tr>      
                        <th class="w-1/4 text-center">Id</th>
                        <th class="w-1/4 text-center">Title</th>
                        <th class="w-1/4 text-center">Album Id</th>
                    </tr>  
                </thead>  
                <tbody>    
                    <tr v-for="song in songs" :key="song.id">      
                        <td class="text-center">{{ song.id }}</td>      
                        <td class="text-center">{{ song.title }}</td>
                        <td class="text-center">{{ song.album_id }}</td>
                    </tr>
                </tbody>
            </table>
        </div> 

    </div>
</template>