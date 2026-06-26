<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArtworkGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    // Artworks in this group
    public function artworks()
    {
        return $this->belongsToMany(Artwork::class, 'artwork_artwork_group');
    }

    // Customers who prefer this group
    public function preferredByCustomers()
    {
        return $this->belongsToMany(Customer::class, 'customer_preferred_groups');
    }
}
