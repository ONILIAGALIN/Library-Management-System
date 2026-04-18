<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'category_id',
        'author_id',
        'publisher_id',
        'title',
        'isbn',
        'published_date',
        'available_copies',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

}
