<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'artwork',
        'genre',
    ];
    

    public function songs(): HasMany {
        return $this->hasMany(Songs::class);
    }

    public function artist(): BelongsTo {
        return $this->belongsTo(Artist::class);
    }

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class, 'user_album');
    }
}
