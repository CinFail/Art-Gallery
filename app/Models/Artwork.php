<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artwork extends Model
{
    use HasFactory, SoftDeletes;

    // Supported artwork types
    public const TYPES = [
        'Painting',
        'Sculpture',
        'Photograph',
        'Lithograph',
        'Drawing',
        'Print',
        'Mixed Media',
        'Digital Art',
        'Watercolor',
        'Other',
    ];

    protected $fillable = [
        'artist_id',
        'title',
        'year_created',
        'art_type',
        'price',
        'description',
        'image_path',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    // Artwork belongs to one artist
    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    // Artwork can belong to many groups
    public function groups()
    {
        return $this->belongsToMany(ArtworkGroup::class, 'artwork_artwork_group');
    }

    // Artwork can have many tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'artwork_tag');
    }
}
