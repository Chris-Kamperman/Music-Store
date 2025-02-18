<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Album::with(['artist', 'songs'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'artist_id' => 'required|exists:artists,id',
            'genre' => 'required',
            'artwork' => 'required|file|image',
        ]);

        $filepath = $request->file('artwork')->store('albums', 'public');
        $data = [
            'title' => $request->title,
            'artist_id' => $request->artist_id,
            'genre' => $request->genre,
            'artwork' => $filepath,
        ];

        return Album::create($data)->load('artist');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Album::with(['artist', 'songs'])->FindOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $album = Album::FindOrFail($id);
        $album->update($request->all());

        return $album;
    }

    /**
     * Add the album in question to the user's collection.
     */
    public function buyAlbum(Request $request, string $id)
    {
        $userId = $request->user()->id;
        $album = Album::FindOrFail($id);

        // Add the album to the user's collection, but only if it's not already there
        $album->users()->syncWithoutDetaching($userId);
        return response(200);
    }

    /**
     * Get all user albums.
     */
    public function getUserAlbums(Request $request) {
        $userId = $request->user()->id;
        return Album::with(['artist', 'songs'])->whereHas('users', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Album::destroy($id);

        return response(204);
    }
}
