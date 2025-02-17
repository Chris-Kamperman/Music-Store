<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Song;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'artwork',
        'genre',

        'artist_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    

    public function songs(): HasMany {
        return $this->hasMany(Song::class);
    }

    public function artist(): BelongsTo {
        return $this->belongsTo(Artist::class);
    }

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class, 'user_album');
    }
}
