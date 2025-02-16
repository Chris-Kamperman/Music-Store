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
        return Album::with('artist')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return Album::create($request->all());
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Album::destroy($id);

        return response()->json(null, 204);
    }
}
