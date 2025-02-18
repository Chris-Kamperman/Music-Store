<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Song;

class SongController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Song::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'album_id' => 'required|exists:albums,id',
            'file' => 'required|file|mimes:mp3',
        ]);

        $filepath = $request->file('file')->store('songs', 'local');

        $data = [
            'title' => $request->title,
            'album_id' => $request->album_id,
            'file' => $filepath,
        ];

        return Song::create($data);
    }

    /**
     * Download the specified resource.
     */
    public function download(string $id) {
        $song = Song::findOrFail($id);

        if(!Storage::disk('local')->exists($song->file)) {
            return response('File not found', 404);
        }

        return response()->download(storage_path('app/private/' . $song->file), $song->title . '.mp3');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Song::FindOrFail($id);
    }
}
