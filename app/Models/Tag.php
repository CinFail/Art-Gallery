<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'color'];

    public function artworks()
    {
        return $this->belongsToMany(Artwork::class, 'artwork_tag');
    }
}
