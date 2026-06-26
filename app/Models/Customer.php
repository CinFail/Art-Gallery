<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'total_spent',
    ];

    protected function casts(): array
    {
        return [
            'total_spent' => 'decimal:2',
        ];
    }

    // Preferred artists
    public function preferredArtists()
    {
        return $this->belongsToMany(Artist::class, 'customer_preferred_artists');
    }

    // Preferred artwork groups
    public function preferredGroups()
    {
        return $this->belongsToMany(ArtworkGroup::class, 'customer_preferred_groups');
    }
}
