<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'album_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }
}
