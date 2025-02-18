<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Song;

class EnsureSongOwned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $albumId = Song::findOrFail($request->route('id'))->album_id;
        $album = $request->user()->albums->find($albumId) ? true : false;

        if (!$album) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
