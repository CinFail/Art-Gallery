<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artist extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'birthplace',
        'age',
        'art_style',
        'bio',
    ];

    // An artist has many artworks
    public function artworks()
    {
        return $this->hasMany(Artwork::class);
    }

    // Customers who prefer this artist
    public function preferredByCustomers()
    {
        return $this->belongsToMany(Customer::class, 'customer_preferred_artists');
    }
}
