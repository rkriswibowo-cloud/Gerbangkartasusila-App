<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'address',
        'latitude', 'longitude', 'ticket_price', 'opening_hours',
        'thumbnail', 'is_featured', 'status'
    ];

    public function category()
    {
        return $this->belongsTo(DestinationCategory::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(DestinationImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
